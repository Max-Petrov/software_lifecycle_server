<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

/**
 *
 * @author maxim
 */
interface IWaypointRepository extends IRepository, IAgregateEntityRepository
{
    public function getWaypointsByRouteJob($routeJobId);
    public function getWaypointsByConditions($routeJobId = null, $periodStart = null, $periodFinish = null, $statusId = null,
            $typeId = null, $limit = 100, $offset = 0);
}
