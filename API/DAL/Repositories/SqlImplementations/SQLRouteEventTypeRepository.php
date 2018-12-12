<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IRouteEventTypeRepository;
use Kenguru\Logistic\API\DAL\Entities\RouteEventType;

/**
 * Description of SQLRouteEventTypeRepository
 *
 * @author maxim
 */
class SQLRouteEventTypeRepository extends SQLRepository implements IRouteEventTypeRepository
{
    public function __construct() 
    {
        parent::__construct("route_event_types", RouteEventType::class);
    }
}
