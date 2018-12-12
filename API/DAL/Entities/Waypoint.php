<?php

namespace Kenguru\Logistic\API\DAL\Entities;

use Kenguru\Logistic\API\DAL\Entities\WaypointStatus;
use Kenguru\Logistic\API\DAL\Entities\RouteJob;
use Kenguru\Logistic\API\DAL\Entities\WaypointType;

/**
 * Description of Waypoint
 *
 * @author maxim
 */
class Waypoint extends DbEntity
{
    private $timeOfCreation;
    private $address;
    private $pointLatitude;
    private $pointLongitude;
    private $timeStop;
    private $cargo;
    private $arriveTimePlan;
    private $leaveTimePlan;
    private $arriveTimeFact;
    private $leaveTimeFact;
    private $numOfSequence;
    private $routeJobId;
    private $waypointTypeId;
    private $waypointStatusId;
    private $routeJob;
    private $waypointType;
    private $waypointStatus;
            
    function __construct() 
    {
        $this->timeOfCreation = NULL;
        $this->address = NULL;
        $this->pointLatitude = NULL;
        $this->pointLongitude = NULL;
        $this->arriveTimePlan = NULL;
        $this->leaveTimePlan = NULL;
        $this->arriveTimeFact = NULL;
        $this->leaveTimeFact = NULL;
        $this->timeStop = NULL;
        $this->cargo = NULL;
        $this->numOfSequence = NULL;
        $this->routeJobId = NULL;
        $this->waypointTypeId = NULL;
        $this->waypointStatusId = NULL;
        $this->routeJob = NULL;
        $this->waypointType = NULL;
        $this->waypointStatus = NULL;
        parent::__construct();
    }
    
    function getTimeOfCration() 
    {
        return $this->timeOfCreation;
    }
    
    function getAddress() 
    {
        return $this->address;
    }
    
    function getPointLatitude() 
    {
        return $this->pointLatitude;
    }

    function getPointLongitude() 
    {
        return $this->pointLongitude;
    }

    
    function getTimeStop() 
    {
        return $this->timeStop;
    }

    function getCargo() 
    {
        return $this->cargo;
    }
    
    function getArriveTimePlan() 
    {
        return $this->arriveTimePlan;
    }

    function getLeaveTimePlan() 
    {
        return $this->leaveTimePlan;
    }
    
    function getArriveTimeFact() 
    {
        return $this->arriveTimeFact;
    }

    function getLeaveTimeFact() 
    {
        return $this->leaveTimeFact;
    }

    function getNumOfSequence() 
    {
        return $this->numOfSequence;
    }

    function getRouteJobId() 
    {
        return $this->routeJobId;
    }

    function getWaypointTypeId() 
    {
        return $this->waypointTypeId;
    }
    
    function getWaypointStatusId() 
    {
        return $this->waypointStatusId;
    }

    function getRouteJob() 
    {
        return $this->routeJob;
    }

    function getWaypointType() 
    {
        return $this->waypointType;
    }
    
    function getWaypointStatus() 
    {
        return $this->waypointStatus;
    }

    function setTimeOfCreation($time) 
    {
        $this->timeOfCreation = $time;
    }
    
    function setAddress($address) 
    {
        $this->address = $address;
    }
    
    function setPointLatitude($pointLatitude) 
    {
        $this->pointLatitude = $pointLatitude;
    }

    function setPointLongitude($pointLongitude) 
    {
        $this->pointLongitude = $pointLongitude;
    }

    function setTimeStop($timeStop) 
    {
        $this->timeStop = $timeStop;
    }

    function setCargo($cargo) 
    {
        $this->cargo = $cargo;
    }
    
    function setArriveTimePlan($arriveTimePlan) 
    {
        $this->arriveTimePlan = $arriveTimePlan;
    }

    function setLeaveTimePlan($leaveTimePlan) 
    {
        $this->leaveTimePlan = $leaveTimePlan;
    }

    function setArriveTimeFact($arriveTimeFact) 
    {
        $this->arriveTimeFact = $arriveTimeFact;
    }

    function setLeaveTimeFact($leaveTimeFact) 
    {
        $this->leaveTimeFact = $leaveTimeFact;
    }

    function setNumOfSequence($numOfSequence) 
    {
        $this->numOfSequence = $numOfSequence;
    }

    function setRouteJobId($routeJobId) 
    {
        $this->routeJobId = $routeJobId;
    }

    function setWaypointTypeId($waypointTypeId) 
    {
        $this->waypointTypeId = $waypointTypeId;
    }
    
    function setWaypointStatusId($waypointStatusId) 
    {
        $this->waypointStatusId = $waypointStatusId;
    }

    function setRouteJob(RouteJob $routeJob) 
    {
        $this->routeJob = $routeJob;
        $this->routeJobId = $routeJob->getId();
    }

    function setWaypointType(WaypointType $waypointType) 
    {
        $this->waypointType = $waypointType;
        $this->waypointTypeId = $waypointType->getId();
    }
    
    function setWaypointStatus(WaypointStatus $waypointStatus) 
    {
        $this->waypointStatus = $waypointStatus;
        $this->waypointStatusId = $waypointStatus->getId();
    }

    public function getData() 
    {
        $result = array(
            'id' => $this->id,
            'time_of_creation' => $this->timeOfCreation,
            'point_address' => $this->address,
            'point_latitude' => $this->pointLatitude,
            'point_longitude' => $this->pointLongitude,
            'time_stop_expected' => $this->timeStop,
            'cargo' => $this->cargo,
            'arrive_time_plan' => $this->arriveTimePlan,
            'leave_time_plan' => $this->leaveTimePlan,
            'arrive_time_fact' => $this->arriveTimeFact,
            'leave_time_fact' => $this->leaveTimeFact,
            'num_seq' => $this->numOfSequence,
            'route_job_id' => $this->routeJobId,
            'waypoint_type_id' => $this->waypointTypeId,
            'waypoint_status_id' => $this->waypointStatusId,
            'route_job' => $this->routeJob,
            'waypoint_type' => $this->waypointType,
            'waypoint_status' => $this->waypointStatus
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->timeOfCreation = $values['time_of_creation'];
        $this->address = $values['point_address'];
        $this->pointLatitude = $values['point_latitude'];
        $this->pointLongitude = $values['point_longitude'];
        $this->timeStop = $values['time_stop_expected'];
        $this->cargo = $values['cargo'];
        $this->arriveTimePlan = $values['arrive_time_plan'];
        $this->leaveTimePlan = $values['leave_time_plan'];
        $this->arriveTimeFact = $values['arrive_time_fact'];
        $this->leaveTimeFact = $values['leave_time_fact'];
        $this->numOfSequence = $values['num_seq'];
        $this->routeJobId = $values['route_job_id'];
        $this->waypointTypeId = $values['waypoint_type_id'];
        $this->waypointStatusId = $values['waypoint_status_id'];
        $this->routeJob = $values['route_job'];
//        $this->waypointType = $values['waypoint_type'];
 //       $this->waypointStatus = $values['waypoint_status'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'time_of_creation' => $this->timeOfCreation,
            'point_address' => $this->address,
            'point_latitude' => $this->pointLatitude,
            'point_longitude' => $this->pointLongitude,
            'time_stop_expected' => $this->timeStop,
            'cargo' => $this->cargo,
            'arrive_time_plan' => $this->arriveTimePlan,
            'leave_time_plan' => $this->leaveTimePlan,
            'arrive_time_fact' => $this->arriveTimeFact,
            'leave_time_fact' => $this->leaveTimeFact,
            'num_seq' => $this->numOfSequence,
            'route_job_id' => $this->routeJobId,
            'waypoint_type_id' => $this->waypointTypeId,
            'waypoint_status_id' => $this->waypointStatusId
        );
        return $result;
    }

}
