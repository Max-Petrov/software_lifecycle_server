<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

/**
 *
 * @author maxim
 */
interface IDriverRegistrationRepository extends IRepository
{
    public function getDriverRegistrationsByConditions($actual = 1, $periodStart = null, $periodFinish = null, $driverId = null, $deviceId = null, $vehicleId = null, $limit = 100, $offset = 0);
}
