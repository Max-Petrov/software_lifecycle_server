<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

use Kenguru\Logistic\API\DAL\Entities\DbEntity;

/**
 *
 * @author maxim
 */
interface IRepository 
{
    function getEntries($limit = 100, $offset = 0);
    function getEntryById($id);
    function updateEntry(DbEntity $entity);
    function insertEntry(DbEntity $entity);
    function insertEntries(array $entities);
    function deleteEntry($id);
}
