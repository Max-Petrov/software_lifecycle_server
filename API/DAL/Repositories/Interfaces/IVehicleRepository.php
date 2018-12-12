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
interface IVehicleRepository extends IRepository, IAgregateEntityRepository
{
    public function getVehiclesByConditions($modelId = null, $statusId = null, $number = null, $limit = 100, $offset = 0);
}
