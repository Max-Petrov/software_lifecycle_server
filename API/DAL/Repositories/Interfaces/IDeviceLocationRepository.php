<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

use Kenguru\Logistic\API\DAL\Entities\DeviceLocation;

/**
 *
 * @author maxim
 */
interface IDeviceLocationRepository extends IRepository
{
    public function getLastDeviceLocationByDevice($deviceId);
    public function getLocationsByConditions($deviceId = null, $periodStart = null, $periodFinish = null, $limit = 100, $offset = 0);
}
