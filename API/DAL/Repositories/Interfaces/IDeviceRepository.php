<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

/**
 *
 * @author maxim
 */
interface IDeviceRepository extends IRepository, IAgregateEntityRepository
{
    public function getDevicesByConditions($modelId = null, $statusId = null, $phoneNumber = null, $limit = 100, $offset = 0);
}
