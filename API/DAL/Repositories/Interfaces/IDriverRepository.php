<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

/**
 *
 * @author maxim
 */
interface IDriverRepository extends IRepository, IAgregateEntityRepository
{
    public function getDriverByConditions($statusId = null, $limit = 100, $offset = 0);
}
