<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IWaypointRepository;
use Kenguru\Logistic\API\DAL\Entities\Waypoint;
use Kenguru\Logistic\API\DAL\Entities\WaypointStatus;
use Kenguru\Logistic\API\DAL\Entities\WaypointType;
use Kenguru\Logistic\API\DAL\Repositories\RepositoryProvider;

/**
 * Description of SQLWaypointRepository
 *
 * @author maxim
 */
class SQLWaypointRepository extends SQLRepository implements IWaypointRepository
{
    public function __construct() 
    {
        parent::__construct("waypoints", Waypoint::class);
    }

    public function getEntitiesWithAgregates($limit = 100, $offset = 0) 
    {
        $sql = "SELECT w.id, w.time_of_creation, w.point_address, w.point_latitude, w.point_longitude, w.time_stop_expected, w.cargo, w.arrive_time_plan,"
                . " w.leave_time_plan, w.arrive_time_fact, w.leave_time_fact, w.num_seq, "
                . "w.route_job_id, s.id status_id, s.name status_name, t.id type_id, t.name type_name"
                . " FROM waypoints w "
                . "JOIN waypoint_statuses s ON w.waypoint_status_id = s.id "
                . "JOIN waypoint_types t ON w.waypoint_type_id = t.id "
                . "LIMIT ?, ?";
        
        $waypoints = array();
        $bindParams = array(
            array(1, $offset, \PDO::PARAM_INT),
            array(2, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $waypoint = new Waypoint();
            $type = new WaypointType;
            $status = new WaypointStatus();
            $type->setId($entry['type_id']);
            $type->setName($entry['type_name']);
            $status->setId($entry['status_id']);
            $status->setName($entry['status_name']);
            $waypoint->setId($entry['id']);
            $waypoint->setTimeOfCreation($entry['time_of_creation']);
            $waypoint->setAddress($entry['point_address']);
            $waypoint->setPointLatitude($entry['point_latitude']);
            $waypoint->setPointLongitude($entry['point_longitude']);
            $waypoint->setCargo($entry['cargo']);
            $waypoint->setLeaveTimeFact($entry['leave_time_fact']);
            $waypoint->setLeaveTimePlan($entry['leave_time_plan']);
            $waypoint->setArriveTimeFact($entry['arrive_time_fact']);
            $waypoint->setArriveTimePlan($entry['arrive_time_plan']);
            $waypoint->setNumOfSequence($entry['num_seq']);
            $waypoint->setRouteJobId($entry['route_job_id']);
            $waypoint->setTimeStop($entry['time_stop_expected']);
            $waypoint->setWaypointStatus($status);
            $waypoint->setWaypointType($type);
            $waypoints[$waypoint->getId()] = $waypoint;
            //array_push($waypoints, $waypoint);
        }
        return $waypoints;
    }

    public function getEntityWithAgregatesById($id) 
    {
        $sql = "SELECT w.id, w.time_of_creation, w.point_address, w.point_latitude, w.point_longitude, w.time_stop_expected, w.cargo, w.arrive_time_plan, "
                . "w.leave_time_plan, w.arrive_time_fact, w.leave_time_fact, w.num_seq, "
                . "w.route_job_id, s.id status_id, s.name status_name, t.id type_id, t.name type_name"
                . " FROM waypoints w "
                . "JOIN waypoint_statuses s ON w.waypoint_status_id = s.id "
                . "JOIN waypoint_types t ON w.waypoint_type_id = t.id "
                . "WHERE w.id = ?";
        
        $args = array($id);
        $stmt = $this->db->run($sql, $args);
        $entry = $stmt->fetch();
        
        $waypoint = new Waypoint();
        $type = new WaypointType;
        $status = new WaypointStatus();
        $type->setId($entry['type_id']);
        $type->setName($entry['type_name']);
        $status->setId($entry['status_id']);
        $status->setName($entry['status_name']);
        $waypoint->setId($entry['id']);
        $waypoint->setTimeOfCreation($entry['time_of_creation']);
        $waypoint->setAddress($entry['point_address']);
        $waypoint->setPointLatitude($entry['point_latitude']);
        $waypoint->setPointLongitude($entry['point_longitude']);
        $waypoint->setCargo($entry['cargo']);
        $waypoint->setLeaveTimeFact($entry['leave_time_fact']);
        $waypoint->setLeaveTimePlan($entry['leave_time_plan']);
        $waypoint->setArriveTimeFact($entry['arrive_time_fact']);
        $waypoint->setArriveTimePlan($entry['arrive_time_plan']);
        $waypoint->setNumOfSequence($entry['num_seq']);
        $waypoint->setRouteJobId($entry['route_job_id']);
        $waypoint->setTimeStop($entry['time_stop_expected']);
        $waypoint->setWaypointStatus($status);
        $waypoint->setWaypointType($type);
        
        return $waypoint;
    }

    public function getWaypointsByRouteJob($routeJobId) 
    {
        $sql = "SELECT w.id, w.point_address, w.point_latitude, w.point_longitude, w.time_stop_expected, w.cargo, w.arrive_time_plan, "
                . "w.leave_time_plan, w.arrive_time_fact, w.leave_time_fact, w.num_seq, "
                . "w.route_job_id, s.id status_id, s.name status_name, t.id type_id, t.name type_name"
                . " FROM waypoints w "
                . "JOIN waypoint_statuses s ON w.waypoint_status_id = s.id "
                . "JOIN waypoint_types t ON w.waypoint_type_id = t.id "
                . "WHERE w.route_job_id = ?";
        
        $waypoints = array();
        $bindParams = array(
            array(1, $routeJobId, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $waypoint = new Waypoint();
            $type = new WaypointType;
            $status = new WaypointStatus();
            $type->setId($entry['type_id']);
            $type->setName($entry['type_name']);
            $status->setId($entry['status_id']);
            $status->setName($entry['status_name']);
            $waypoint->setId($entry['id']);
            $waypoint->setAddress($entry['point_address']);
            $waypoint->setPointLatitude($entry['point_latitude']);
            $waypoint->setPointLongitude($entry['point_longitude']);
            $waypoint->setCargo($entry['cargo']);
            $waypoint->setLeaveTimeFact($entry['leave_time_fact']);
            $waypoint->setLeaveTimePlan($entry['leave_time_plan']);
            $waypoint->setArriveTimeFact($entry['arrive_time_fact']);
            $waypoint->setArriveTimePlan($entry['arrive_time_plan']);
            $waypoint->setNumOfSequence($entry['num_seq']);
            $waypoint->setRouteJobId($entry['route_job_id']);
            $waypoint->setTimeStop($entry['time_stop_expected']);
            $waypoint->setWaypointStatus($status);
            $waypoint->setWaypointType($type);
            $waypoints[$waypoint->getId()] = $waypoint;
            //array_push($waypoints, $waypoint);
        }
        return $waypoints;
    }

    public function getWaypointsByConditions($routeJobId = null, $periodStart = null, $periodFinish = null, $statusId = null, $typeId = null, $limit = 100, $offset = 0)
    {
        $where = array();
        
        if (is_numeric($routeJobId)){
            array_push($where, array('route_job_id', '=', $routeJobId));
            $orderBy['num_seq'] = 'ASC';
        }
        else {
        	$orderBy = array('time_of_creation' => 'DESC');
        }
        if (is_string($periodStart)){
            array_push($where, array('arrive_time_fact', '>=', $periodStart));
        }
        if (is_string($periodFinish)){
            array_push($where, array('arrive_time_fact', '<=', $periodFinish));
        }
        if (is_numeric($statusId)){
            array_push($where, array('waypoint_status_id', '=', $statusId));
        }
        if (is_numeric($typeId)){
            array_push($where, array('waypoint_type_id', '=', $typeId));
        }
        
        $waypoints = $this->getEntriesWithWhere($where, $orderBy, $limit, $offset);
        
        $provider = new RepositoryProvider();
        $typeRepos = $provider->getWaypointTypeRepository();
        $types = $typeRepos->getEntries();
        $statusRepos = $provider->getWaypointStatusRepository();
        $statuses = $statusRepos->getEntries();
        
        foreach (array_values($waypoints) as $waypoint){
            $waypoint->setWaypointType($types[$waypoint->getWaypointTypeId()]);
            $waypoint->setWaypointStatus($statuses[$waypoint->getWaypointStatusId()]);
        }
        return $waypoints;
    }
    
    public function updateNumber($id, $number = null)
    {
        $sql = "UPDATE waypoints SET num_seq = :num WHERE id = :id";
        $args = array('num' => $number, 'id' => $id);
        $stmt = $this->db->run($sql, $args);
        return $stmt->fetch();
    }
}
