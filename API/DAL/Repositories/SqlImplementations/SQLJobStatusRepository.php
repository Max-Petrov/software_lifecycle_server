<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IJobStatusRepository;
use Kenguru\Logistic\API\DAL\Entities\JobStatus;
use Kenguru\Logistic\API\DAL\Database\SQLDatabase;

/**
 * Description of SQLJobStatusRepository
 *
 * @author maxim
 */
class SQLJobStatusRepository extends SQLRepository implements IJobStatusRepository
{
    public function __construct() 
    {
        parent::__construct("job_statuses", JobStatus::class);
    }

}
