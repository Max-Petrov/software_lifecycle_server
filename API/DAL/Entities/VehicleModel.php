<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of VehicleModel
 *
 * @author maxim
 */
class VehicleModel extends DbEntity
{ 
    private $name;
    private $carrying;  
    
    function __construct()
    {
        $this->name = NULL;
        $this->carrying = NULL;
        parent::__construct();
    }
    
    function getName()
    {
        return $this->name;
    }

    function getCarrying() 
    {
        return $this->carrying;
    }

    function setName($name) 
    {
        $this->name = $name;
    }

    function setCarrying($carrying) 
    {
        $this->carrying = $carrying;
    }

    public function getData() 
    {
        $result = array(
            'id' => $this->id,
            'name' => $this->name,
            'carrying' => $this->carrying
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->name = $values['name'];
        $this->carrying = $values['carrying'];
    }

    public function getDbSetData() 
    {
        return $this->getData();
    }

}
