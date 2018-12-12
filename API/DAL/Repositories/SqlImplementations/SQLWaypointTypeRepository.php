<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IWaypointTypeRepository;
use Kenguru\Logistic\API\DAL\Entities\WaypointType;

/**
 * Description of SQLWaypointTypeRepository
 *
 * @author maxim
 */
class SQLWaypointTypeRepository extends SQLRepository implements IWaypointTypeRepository
{
    public function __construct() 
    {
        parent::__construct("waypoint_types", WaypointType::class);
    }
}
