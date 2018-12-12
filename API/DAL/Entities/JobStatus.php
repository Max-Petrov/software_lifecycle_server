<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of JobStatus
 *
 * @author maxim
 */
class JobStatus extends DbEntity
{ 
    private $name;

    public function __construct()
    {
        $this->id = 0;
        $this->name = '';
    }
    
    public function __set($name, $value) {}

    function getName()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    public function setData($values)
    {
        $this->id = $values['id'];
        $this->name = $values['name'];
    }
    
    public function getData()
    {
        $result = array(
            'id' => $this->id,
            'name' => $this->name
        );
        return $result;
    }

    public function getDbSetData() 
    {
        return $this->getData();
    }

}
