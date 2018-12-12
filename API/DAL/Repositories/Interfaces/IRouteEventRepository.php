<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

/**
 *
 * @author maxim
 */
interface IRouteEventRepository extends IRepository, IAgregateEntityRepository
{
    public function getRouteEventsByConditions($waypointId = null, $routeEventTypeId = null, $periodStart = null, $periodFinish = null, $limit = 100, $offset = 0);
}
