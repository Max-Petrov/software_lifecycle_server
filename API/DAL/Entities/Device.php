<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of Device
 *
 * @author maxim
 */
class Device extends DbEntity
{
    private $phoneNumber;
    private $modelId;
    private $statusId;
    private $model;
    private $status;
    
    function __construct() 
    {
        $this->phoneNumber = NULL;
        $this->modelId = NULL;
        $this->model = NULL;
        $this->statusId = NULL;
        $this->status = NULL;
        parent::__construct();
    }

    public function getPhoneNumber() 
    {
        return $this->phoneNumber;
    }
    
    public function getModelId()
    {
        return $this->modelId;
    }
    
    public function getStatusId()
    {
        return $this->statusId;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getModel()
    {
        return $this->model;
    }
    
    public function setPhoneNumber($phoneNumber) 
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;
    }
    
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;
    }

    public function setStatus(DeviceStatus $status) 
    {
        $this->status = $status;
        $this->statusId = $status->getId();
    }
    
    public function setModel(DeviceModel $model) 
    {
        $this->model = $model;
        $this->modelId = $model->getId();
    }

    public function getData()
    {
        $result = array(
            'id' => $this->id,
            'phone_number' => $this->phoneNumber,
            'device_model_id' => $this->modelId,
            'device_status_Id' => $this->statusId,
            'model' => $this->model,
            'status' => $this->status
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->phoneNumber = $values['phone_number'];
        $this->modelId = $values['device_model_id'];
        $this->statusId = $values['device_status_id'];
        $this->model = $values['model'];
        $this->status = $values['status'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'phone_number' => $this->phoneNumber,
            'device_model_id' => $this->modelId,
            'device_status_Id' => $this->statusId
        );
        return $result;
    }

}
