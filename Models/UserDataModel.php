<?php
namespace Models;
use Models\Repositories\UserDataRepository;

/**
* class UserDataModel Generated by ISV framework generator
* @version 1.1
* @package Models
*
*/
class UserDataModel extends UserDataRepository
{
    /**
    *Method forms define rules for generate forms from this model
    */
    public function forms()
    {
        return [
            'id' => ['type' => 'number',],
            'userId' => ['type' => 'number',],
            'label' => ['type' => 'text',],
            'val' => ['type' => 'text',],
        ];
    }
}
