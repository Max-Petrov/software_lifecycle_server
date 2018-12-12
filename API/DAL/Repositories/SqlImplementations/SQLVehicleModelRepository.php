<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IVehicleModelRepository;
use Kenguru\Logistic\API\DAL\Entities\VehicleModel;

/**
 * Description of SQLVehicleModelRepository
 *
 * @author maxim
 */
class SQLVehicleModelRepository extends SQLRepository implements IVehicleModelRepository
{
    public function __construct() 
    {
        parent::__construct("vehicle_models", VehicleModel::class);
    }
}
