<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of RouteEvent
 *
 * @author maxim
 */
class RouteEvent extends DbEntity
{
    private $eventTime;
    private $routeEventTypeId;
    private $waypointId;
    private $routeEventType;
    private $waypoint;
    
    function __construct() 
    {
        $this->eventTime = NULL;
        $this->routeEventTypeId = NULL;
        $this->waypointId = NULL;
        $this->routeEventType = NULL;
        $this->waypoint = NULL;
        parent::__construct();
    }
    
    function getEventTime() 
    {
        return $this->eventTime;
    }

    function getRouteEventTypeId() 
    {
        return $this->routeEventTypeId;
    }

    function getWaypointId() 
    {
        return $this->waypointId;
    }

    function getRouteEventType() 
    {
        return $this->routeEventType;
    }

    function getWaypoint() 
    {
        return $this->waypoint;
    }

    function setEventTime($eventTime) 
    {
        $this->eventTime = $eventTime;
    }

    function setRouteEventTypeId($routeEventTypeId) 
    {
        $this->routeEventTypeId = $routeEventTypeId;
    }

    function setWaypointId($waypointId) 
    {
        $this->waypointId = $waypointId;
    }

    function setRouteEventType(RouteEventType $routeEventType) 
    {
        $this->routeEventType = $routeEventType;
    }

    function setWaypoint(Waypoint $waypoint) 
    {
        $this->waypoint = $waypoint;
    }

    public function getData() 
    {
        $result = array(
            'id' => $this->id,
            'event_time' => $this->eventTime,
            'route_event_type_id' => $this->routeEventTypeId,
            'waypoint_id' => $this->waypointId,
            'route_event_type' => $this->routeEventType,
            'waypoint' => $this->waypoint
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->eventTime = $values['event_time'];
        $this->routeEventTypeId = $values['route_event_type_id'];
        $this->waypointId = $values['waypoint_id'];
        $this->routeEventType = $values['route_event_type'];
        $this->waypoint = $values['waypoint'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'event_time' => $this->eventTime,
            'route_event_type_id' => $this->routeEventTypeId,
            'waypoint_id' => $this->waypointId
        );
        return $result;
    }

}
