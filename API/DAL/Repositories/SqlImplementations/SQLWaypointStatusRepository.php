<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IWaypointStatusRepository;
use Kenguru\Logistic\API\DAL\Entities\WaypointStatus;

/**
 * Description of SQLWaypointStatusRepository
 *
 * @author maxim
 */
class SQLWaypointStatusRepository extends SQLRepository implements IWaypointStatusRepository
{
    public function __construct() 
    {
        parent::__construct("waypoint_statuses", WaypointStatus::class);
    }
}
