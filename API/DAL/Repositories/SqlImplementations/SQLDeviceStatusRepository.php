<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceStatusRepository;
use Kenguru\Logistic\API\DAL\Entities\DeviceStatus;

/**
 * Description of SQLDeviceStatusRepository
 *
 * @author maxim
 */
class SQLDeviceStatusRepository extends SQLRepository implements IDeviceStatusRepository
{
    public function __construct() 
    {
        parent::__construct("device_statuses", DeviceStatus::class);
    }
}
