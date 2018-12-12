<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of DriverStatus
 *
 * @author maxim
 */
class DriverStatus extends DbEntity
{
    private $name;
    
    function __construct() 
    {
        $this->name = NULL;
        parent::__construct();
    }
    
    function getName() 
    {
        return $this->name;
    }

    function setName($name) 
    {
        $this->name = $name;
    }

    public function getData()
    {
        $result = array(
            'id' => $this->id,
            'name' => $this->name
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->name = $values['name'];
    }

    public function getDbSetData() 
    {
        return $this->getData();
    }

}
