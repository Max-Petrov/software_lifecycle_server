<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IVehicleStatusRepository;
use Kenguru\Logistic\API\DAL\Entities\VehicleStatus;

/**
 * Description of SQLVehicleStatusRepository
 *
 * @author maxim
 */
class SQLVehicleStatusRepository extends SQLRepository implements IVehicleStatusRepository
{
    public function __construct()
    {
        parent::__construct("vehicle_statuses", VehicleStatus::class);
    }
}
