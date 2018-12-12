<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceModelRepository;
use Kenguru\Logistic\API\DAL\Entities\DeviceModel;

/**
 * Description of SQLDeviceModelRepository
 *
 * @author maxim
 */
class SQLDeviceModelRepository extends SQLRepository implements IDeviceModelRepository
{
    public function __construct() 
    {
        parent::__construct("device_models", DeviceModel::class);
    }
}
