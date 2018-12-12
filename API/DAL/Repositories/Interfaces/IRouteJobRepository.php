<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

/**
 *
 * @author maxim
 */
interface IRouteJobRepository extends IRepository, IAgregateEntityRepository
{
    public function getRouteJobsByDriverRegistrationId($registrationId, $limit = 100, $offset = 0);
    public function getRouteJobsByDriverRegistrationIdAndStatusId($registrationId, $statusId, $limit = 100, $offset = 0);
    public function getRouteJobsByConditions($registrationId = null, $periodStart = null, $periodFinish = null, $statusId = null, $limit = 100, $offset = 0);
    public function getCurrentRouteJobByRegistrationId($registrationId);
}
