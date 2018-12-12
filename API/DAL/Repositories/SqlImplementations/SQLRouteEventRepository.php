<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IRouteEventRepository;
use Kenguru\Logistic\API\DAL\Entities\RouteEvent;
use Kenguru\Logistic\API\DAL\Entities\RouteEventType;
use Kenguru\Logistic\API\DAL\Repositories\RepositoryProvider;

/**
 * Description of SQLRouteEventRepository
 *
 * @author maxim
 */
class SQLRouteEventRepository extends SQLRepository implements IRouteEventRepository
{
    public function __construct() 
    {
        parent::__construct("route_events", RouteEvent::class);
    }

    public function getEntitiesWithAgregates($limit = 100, $offset = 0) 
    {
        $sql = "SELECT r.id id, r.event_time event_time, r.route_event_type_id route_event_type_id, t.name type_name, r.waypoint_id waypoint_id "
                . "FROM route_events r "
                . "JOIN route_event_types t ON r.route_event_type_id = t.id LIMIT ?, ?";
        
        $events = array();
        $bindParams = array(
            array(1, $offset, \PDO::PARAM_INT),
            array(2, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $event = new RouteEvent();
            $type = new RouteEventType();
            $event->setId($entry['id']);
            $event->setEventTime($entry['event_time']);
            $event->setWaypointId($entry['waypoint_id']);
            $type->setId($entry['route_event_type_id']);
            $type->setName($entry['type_name']);
            $event->setRouteEventType($type);
            $events[$event->getId()] = $event;
            //array_push($events, $event);
        }
        return $events;
    }

    public function getEntityWithAgregatesById($id) 
    {
        $sql = "SELECT r.id id, r.event_time event_time, r.route_event_type_id route_event_type_id, t.name type_name, r.waypoint_id waypoint_id "
                . "FROM route_events r "
                . "JOIN route_event_types t ON r.route_event_type_id = t.id "
                . "WHERE r.id = ?";
        
        $args = array($id);
        $stmt = $this->db->run($sql, $args);
        $entry = $stmt->fetch();
        
        $event = new RouteEvent();
        $type = new RouteEventType();
        $event->setId($entry['id']);
        $event->setEventTime($entry['event_time']);
        $event->setWaypointId($entry['waypoint_id']);
        $type->setId($entry['route_event_type_id']);
        $type->setName($entry['type_name']);
        $event->setRouteEventType($type);
        
        return $event;
    }

    public function getRouteEventsByConditions($waypointId = null, $routeEventTypeId = null, $periodStart = null, $periodFinish = null, $limit = 100, $offset = 0) 
    {   
        $where = array();
        if (is_numeric($waypointId)){
            array_push($where, array('waypoint_id', '=', $waypointId));
        }
        if (is_string($periodStart)){
            array_push($where, array('event_time', '>=', $periodStart));
        }
        if (is_string($periodFinish)){
            array_push($where, array('event_time', '<=', $periodFinish));
        }
        if (is_numeric($routeEventTypeId)){
            array_push($where, array('route_event_type_id', '=', $routeEventTypeId));
        }
        $events = $this->getEntriesWithWhere($where, null, $limit, $offset);
        
        $provider = new RepositoryProvider();
        $repos = $provider->getRouteEventTypeRepository();
        $types = $repos->getEntries();
        
        foreach (array_values($events) as $event){
            $event->setRouteEventType($types[$event->getRouteEventTypeId()]);
        }
        
        return $events;
    }
}
