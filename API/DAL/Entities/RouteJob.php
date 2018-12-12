<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of RouteJob
 *
 * @author maxim
 */
class RouteJob extends DbEntity
{
    private $createdTime;
    private $startPointLatitude;
    private $startPointLongitude;
    private $finishPointAddress;
    private $finishPointLatitude;
    private $finishPointLongitude;
    private $startTimePlan;
    private $finishTimePlan;
    private $startTimeFact;
    private $finishTimeFact;
    private $totalDistance;
    private $statusId;
    private $driverRegistrationId;
    private $status;
    private $driverRegistration;
    
    function __construct() 
    {
        $this->createdTime = NULL;
        $this->startPointLatitude = NULL;
        $this->startPointLongitude = NULL;
        $this->finishPointAddress = NULL;
        $this->finishPointLatitude = NULL;
        $this->finishPointLongitude = NULL;
        $this->startTimePlan = NULL;
        $this->finishTimePlan = NULL;
        $this->startTimeFact = NULL;
        $this->finishTimeFact = NULL;
        $this->totalDistance = NULL;
        $this->statusId = NULL;
        $this->driverRegistrationId = NULL;
        $this->status = NULL;
        $this->driverRegistration = NULL;
        parent::__construct();
    }

    function getCreatedTime() 
    {
        return $this->createdTime;
    }
    
    function getStartPointLatitude()
    {
        return $this->startPointLatitude;
    }

    function getStartPointLongitude() 
    {
        return $this->startPointLongitude;
    }

    function getFinishPointAddress() 
    {
        return $this->finishPointAddress;
    }

    function getFinishPointLatitude() 
    {
        return $this->finishPointLatitude;
    }

    function getFinishPointLongitude() 
    {
        return $this->finishPointLongitude;
    }

    function getStartTimePlan() 
    {
        return $this->startTimePlan;
    }

    function getFinishTimePlan() 
    {
        return $this->finishTimePlan;
    }
    
    function getStartTimeFact() 
    {
        return $this->startTimeFact;
    }

    function getFinishTimeFact() 
    {
        return $this->finishTimeFact;
    }

    function getTotalDistance() 
    {
        return $this->totalDistance;
    }

    function getStatusId() 
    {
        return $this->statusId;
    }

    function getDriverRegistrationId() 
    {
        return $this->driverRegistrationId;
    }

    function getStatus() 
    {
        return $this->status;
    }

    function getDriverRegistration() 
    {
        return $this->driverRegistration;
    }

    function setCreatedTime($createdTime) 
    {
        $this->createdTime = $createdTime;
    }
    
    function setStartPointLatitude($startPointLatitude) 
    {
        $this->startPointLatitude = $startPointLatitude;
    }

    function setStartPointLongitude($startPointLongitude) 
    {
        $this->startPointLongitude = $startPointLongitude;
    }

    function setFinishPointAddress($finishPointAddress) 
    {
        $this->finishPointAddress = $finishPointAddress;
    }

    function setFinishPointLatitude($finishPointLatitude) 
    {
        $this->finishPointLatitude = $finishPointLatitude;
    }

    function setFinishPointLongitude($finishPointLongitude)
    {
        $this->finishPointLongitude = $finishPointLongitude;
    }

    function setStartTimePlan($startTimePlan) 
    {
        $this->startTimePlan = $startTimePlan;
    }

    function setFinishTimePlan($finishTimePlan) 
    {
        $this->finishTimePlan = $finishTimePlan;
    }

    function setStartTimeFact($startTimeFact) 
    {
        $this->startTimeFact = $startTimeFact;
    }

    function setFinishTimeFact($finishTimeFact) 
    {
        $this->finishTimeFact = $finishTimeFact;
    }

    function setTotalDistance($totalDistance) 
    {
        $this->totalDistance = $totalDistance;
    }

    function setStatusId($statusId) 
    {
        $this->statusId = $statusId;
    }

    function setDriverRegistrationId($driverRegistrationId) 
    {
        $this->driverRegistrationId = $driverRegistrationId;
    }

    function setStatus(JobStatus $status) 
    {
        $this->status = $status;
        $this->statusId = $status->getId();
    }

    function setDriverRegistration(DriverRegistration $driverRegistration) 
    {
        $this->driverRegistration = $driverRegistration;
        $this->driverRegistrationId = $driverRegistration->getId();
    }

    public function getData() 
    {
        $result = array(
            'id' => $this->id,
            'created_time' => $this->createdTime,
            'start_point_latitude' => $this->startPointLatitude,
            'start_point_longitude' => $this->startPointLongitude,
            'finish_point_address' => $this->finishPointAddress,
            'finish_point_latitude' => $this->finishPointLatitude,
            'finish_point_longitude' => $this->finishPointLongitude,
            'start_time_plan' => $this->startTimePlan,
            'finish_time_plan' => $this->finishTimePlan,
            'start_time_fact' => $this->startTimeFact,
            'finish_time_fact' => $this->finishTimeFact,
            'total_distance' => $this->totalDistance,
            'job_status_id' => $this->statusId,
            'driver_registration_id' => $this->driverRegistrationId,
            'status' => $this->status,
            'driver_registration' => $this->driverRegistration
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->createdTime = $values['created_time'];
        $this->startPointLatitude = $values['start_point_latitude'];
        $this->startPointLongitude = $values['start_point_longitude'];
        $this->finishPointAddress = $values['finish_point_address'];
        $this->finishPointLatitude = $values['finish_point_latitude'];
        $this->finishPointLongitude = $values['finish_point_longitude'];
        $this->startTimePlan = $values['start_time_plan'];
        $this->finishTimePlan = $values['finish_time_plan'];
        $this->startTimeFact = $values['start_time_fact'];
        $this->finishTimeFact = $values['finish_time_fact'];
        $this->totalDistance = $values['total_distance'];
        $this->statusId = $values['job_status_id'];
        $this->driverRegistrationId = $values['driver_registration_id'];
//        $this->status = $values['status'];
//        $this->driverRegistration = $values['driver_registration'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'created_time' => $this->createdTime,
            'start_point_latitude' => $this->startPointLatitude,
            'start_point_longitude' => $this->startPointLongitude,
            'finish_point_address' => $this->finishPointAddress,
            'finish_point_latitude' => $this->finishPointLatitude,
            'finish_point_longitude' => $this->finishPointLongitude,
            'start_time_plan' => $this->startTimePlan,
            'finish_time_plan' => $this->finishTimePlan,
            'start_time_fact' => $this->startTimeFact,
            'finish_time_fact' => $this->finishTimeFact,
            'total_distance' => $this->totalDistance,
            'job_status_id' => $this->statusId,
            'driver_registration_id' => $this->driverRegistrationId
        );
        return $result;
    }

}
