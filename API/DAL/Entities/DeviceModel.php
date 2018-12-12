<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of DeviceModel
 *
 * @author maxim
 */
class DeviceModel extends DbEntity
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
