<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 4/25/17
 * Time: 1:43 PM
 */
namespace Models;

use isv\Http\Request;
use isv\IS;
class SignUpModel extends UsersModel
{
    public function __construct($id)
    {
        parent::__construct($id);
        $userData = new UserDataModel();
        $userData->SetPdoFetchMode(\PDO::FETCH_KEY_PAIR);
        $currentUserData = $userData->findAll('label, val', ['userId' => (int)$id]);
        foreach ($currentUserData as $property => $val){
            if(property_exists($this, $property))
                $this->$property = $val;
        }
    }

    // Default user role
    const DEFAULT_ROLE = 0;
    // Email confirmed or not
    const CONFIRMED_EMAIL = 1;
    // Login statuses
    const USER_NOT_EXISTS = 1;
    const INVALID_PASSWORD = 2;
    const USER_BLOCKED = 3;
    const VERIFICATION_REQUIRED = 4;

    private $labels = [
        'username'=>'username',
        'password' => 'password',
        'passwordMatch' => 'passwordMatch',
        'email' => 'email',
        'name' => 'Name',
        'address' => 'address',
        'phone' => 'phone',
    ];

    private $dataFields = [
        'name' => 'Name',
        'address' => 'address',
        'phone' => 'phone',
    ];

    private $loginFields = [
        'email','username'
    ];

    private $uniqueFields = [
        'email','username'
    ];

    protected $password;
    protected $passwordMatch;
    protected $name='';
    protected $address='';
    protected $phone='';

    
    
    public function setPasswordMatch($passwordMatch)
    {
        $this->passwordMatch = $passwordMatch;
        return $this;
    }
    
    public function getPasswordMatch()
    {
        return  $this->passwordMatch;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

   
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

   
    public function getPhone()
    {
        return $this->phone;
    }

    // All data to be filtered
    public function forms()
    {
        return [
            'username' => ['type' => 'text', 'class' => 'form-control', 'id' => 'username', 'required' => 1],
            'email' => ['type' => 'email', 'class' => 'form-control', 'id' => 'email', 'required' => 1],
            'password' => ['type' => 'password', 'class' => 'form-control', 'id' => 'password', 'required' => 1],
            'passwordMatch' => ['type' => 'password', 'class' => 'form-control', 'id' => 'passwordMatch'],
            'name' => ['type' => 'text', 'class' => 'form-control', 'id' => 'name', 'required' => 1],
            'address' => ['type' => 'text', 'class' => 'form-control', 'id' => 'address', 'required' => 1],
            'phone' => ['type' => 'text', 'class' => 'form-control', 'id' => 'phone', 'required' => 1],
        ];
    }

    private function filter($data)
    {
        $filter = [
            'email' => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_NULL_ON_FAILURE,
            'passwordMatch' => [
                'filter' => FILTER_CALLBACK,
                'options' => function() {
                    return $this->password === $this->passwordMatch;
                }
            ],
          //  'name' => FILTER_NULL_ON_FAILURE,
          //  'address' => FILTER_NULL_ON_FAILURE,
//            'phone' => [
//                'filter' => FILTER_VALIDATE_REGEXP,
//                'options' => [
//                    'regexp' => '/[0-9+]/'
//                ]
//            ]
        ];
        $filtered = filter_var_array($data, $filter);
        foreach ($filtered as $k => $value) {
            if($value === false || $value === null)
                $this->errors[$k] = 'Invalid data format';
        }
        foreach ($this->uniqueFields as $field)
        {
            $model = new UsersModel([$field => $this->$field]);
            if($model && !$model->insert)
                $this->errors[$field] = 'A user with this data is already registered';
        }
     //   \isv\Developer\Developer::dump($this->errors);die();
        return count($this->errors) ? false : true;
    }
// End of filters settings

    /**
     * @deprecated
     * @return bool
     */
    public function validateData(){return true;}

    public function load($array)
    {
        parent::load($array);
        return $this->filter($array);
    }

    public function label($label)
    {
        return isset($this->labels[$label]) ? $this->labels[$label] : NULL;
    }

    public function save()
    {
        $this->password = static::cryptPassword($this->password);
        $this->ip = IS::app()->request()->ip(Request::IP2LONG);
        $this->registerDate = time();
        $this->lastLogin = 0;
        $this->role = static::DEFAULT_ROLE;
        $this->confirmed = 0;
        $this->blocked = 0;
        $this->rememberToken = static::generateToken();

        $userData = [];
        foreach ($this->dataFields as $field => $label)
        {
            if(property_exists($this, $field)) {
                $userData[$field] = ['label' => $label, 'val' => $this->$field];
                unset($this->$field);
            }
        }
        
   //     \isv\Developer\Developer::dump($userData); die();
        
        unset($this->dataFields);
        unset($this->labels);
        unset($this->passwordMatch);
        $this->Adapter->beginTransaction();
        try{
            $id = parent::save();
            foreach ($userData as $k => $data)
            {
                $data['userId'] = $id;
                $model = new UserDataModel();
                $model->load($data);
                $model->save();
            }
            $this->Adapter->commit();
            return true;
        }catch (\PDOException $e){
            $this->Adapter->rollBack();
            var_dump("Database error: ".$e->getMessage());die();
        }
    }

    public function login($login, $password, $remember=null)
    {
        foreach ($this->loginFields as $fieldName)
        {
            $model = new UsersModel([$fieldName => $login]);
            if($model && !$model->insert)
            {               // \isv\Developer\Developer::dump($model->confirmed);
                if ($password !== $model->getPassword())
                   return static::INVALID_PASSWORD;
//                elseif($model->getBlocked())
//                    return static::USER_BLOCKED;
//                elseif(!$model->confirmed)
//                    return static::VERIFICATION_REQUIRED;
                else {
                    $token = static::generateToken();
                    if($remember)
                        setcookie('remember', $token, strtotime('+1 year'), '/', $_SERVER['HTTP_HOST']);
                    $model->setRememberToken($token)
                        ->setLastLogin(time())
                        ->save();
                    IS::app()->session()->container('user')->id($model->getId());
                    return true;
                }
                break;
            }
        }
        return static::USER_NOT_EXISTS;
    }

    public static function cryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function generateToken()
    {
        return hash('sha256', 'wergwergwerge');
       // return 'wergwergwerge';
    }
}