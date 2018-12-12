<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of Vehicle
 *
 * @author maxim
 */
class Vehicle extends DbEntity
{ 
    private $number;
    private $modelId;
    private $statusId;  
    private $model;
    private $status;
    
    function __construct() 
    {
        $this->number = NULL;
        $this->modelId = NULL;
        $this->statusId = NULL;
        $this->model = NULL;
        $this->status = NULL;
        parent::__construct();
    }
    
    function getNumber() 
    {
        return $this->number;
    }

    function getModelId() 
    {
        return $this->modelId;
    }

    function getStatusId() 
    {
        return $this->statusId;
    }

    function getModel() 
    {
        return $this->model;
    }

    function getStatus() 
    {
        return $this->status;
    }

    function setNumber($number) 
    {
        $this->number = $number;
    }

    function setModelId($modelId) 
    {
        $this->modelId = $modelId;
    }

    function setStatusId($statusId) 
    {
        $this->statusId = $statusId;
    }

    function setModel(VehicleModel $model) 
    {
        $this->model = $model;
        $this->modelId = $model->getId();
    }

    function setStatus(VehicleStatus $status) 
    {
        $this->status = $status;
        $this->statusId = $status->getId();
    }

    public function getData() 
    {
        $result = array(
            'id' => $this->id,
            'number' => $this->number,
            'vehicle_model_id' => $this->modelId,
            'vehicle_status_id' => $this->statusId,
            'model' => $this->model,
            'status' => $this->status
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->number = $values['number'];
        $this->modelId = $values['vehicle_model_id'];
        $this->statusId = $values['vehicle_status_id'];
        $this->model = $values['model'];
        $this->status = $values['status'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'number' => $this->number,
            'vehicle_model_id' => $this->modelId,
            'vehicle_status_id' => $this->statusId
        );
        return $result;
    }

}
