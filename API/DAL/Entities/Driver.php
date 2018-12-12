<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of Driver
 *
 * @author maxim
 */
class Driver extends DbEntity 
{
    private $name;
    private $statusId;
    private $status;
    
    function __construct() 
    {
        $this->name = NULL;
        $this->statusId = NULL;
        $this->status = NULL;
        parent::__construct();
    }
    
    function getName() 
    {
        return $this->name;
    }

    function getStatusId() 
    {
        return $this->statusId;
    }

    function getStatus() 
    {
        return $this->status;
    }

    function setName($name) 
    {
        $this->name = $name;
    }

    function setStatusId($statusId) 
    {
        $this->statusId = $statusId;
    }

    function setStatus(DriverStatus $status) 
    {
        $this->status = $status;
        $this->statusId = $status->getId();
    }

    public function getData() 
    {
        $result = array(
            'id' => $this->id,
            'name' => $this->name,
            'driver_status_id' => $this->statusId,
            'status' => $this->status
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->name = $values['name'];
        $this->statusId = $values['driver_status_id'];
        $this->status = $values['status'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'name' => $this->name,
            'driver_status_id' => $this->statusId
        );
        return $result;
    }

}
