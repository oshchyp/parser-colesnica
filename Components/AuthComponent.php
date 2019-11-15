<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 4/25/17
 * Time: 11:09 AM
 */
namespace Component;

use isv\Component\ISVComponent;
use isv\Component\ISVComponentInterface;
use isv\IS;
use Models\SignUpModel;
use Models\UsersModel;
/**
 * @version 2.0
 * Class AuthComponent
 * @package Component
 */
class AuthComponent extends ISVComponent implements ISVComponentInterface
{
    /**
     * @var $instance
     */
    private $instance;

    public $id;

    public function init()
    {
        // If user object exists in SESSION load to this instance
        if( IS::app()->session()->container('user')->id() )
        {
            $this->id = (int)IS::app()->session()->container('user')->id();
        }
    }
    /**
     * Check if user is logged in
     * @return bool
     */
    public function isAuth()
    {
        return $this->id ? true : false;
    }

    /**
     * This method returns logged in user data
     * @param $name
     * @return bool
     */
    public function __get($name)
    {
        if(!$this->isAuth())
            return false;
        if(!$this->instance)
        {
            $this->instance = new SignUpModel((int)$this->id);
        }
        // Method name like model functions names.
        $realMethodName = 'get'.ucfirst($name);
        return method_exists($this->instance, $realMethodName) ? $this->instance->$realMethodName() : NULL;
    }

    /**
     * @return void
     */
    public function logout()
    {
        IS::app()->session()->removeContainer('user');
        setcookie('remember', NULL, time()-3600, '/', $_SERVER['HTTP_HOST']);
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     * @return bool|string
     */
    public function changePassword($oldPassword, $newPassword)
    {
        $model = new UsersModel((int)IS::app()->user()->id);
        if($model->insert)
            return false;
        if(!password_verify($oldPassword, $model->getPassword()))
        {
            return false;
        }
        return $model->setPassword(password_hash($newPassword, PASSWORD_DEFAULT))->save();
    }
}