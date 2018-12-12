<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDriverStatusRepository;
use Kenguru\Logistic\API\DAL\Entities\DriverStatus;

/**
 * Description of SQLDriverStatusRepository
 *
 * @author maxim
 */
class SQLDriverStatusRepository extends SQLRepository implements IDriverStatusRepository
{
    public function __construct() 
    {
        parent::__construct("driver_statuses", DriverStatus::class);
    }
}
