<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDriverRepository;
use Kenguru\Logistic\API\DAL\Entities\Driver;
use Kenguru\Logistic\API\DAL\Entities\DriverStatus;
use Kenguru\Logistic\API\DAL\Repositories\RepositoryProvider;

/**
 * Description of SQLDriverRepository
 *
 * @author maxim
 */
class SQLDriverRepository extends SQLRepository implements IDriverRepository
{
    public function __construct() 
    {
        parent::__construct("drivers", Driver::class);
    }

    public function getEntitiesWithAgregates($limit = 100, $offset = 0) 
    {
        $sql = "SELECT d.id id, d.name driver_name, driver_status_id, s.name status_name FROM drivers d "
                . "JOIN driver_statuses s ON driver_status_id = s.id LIMIT ?, ?";
        
        $drivers = array();
        $bindParams = array(
            array(1, $offset, \PDO::PARAM_INT),
            array(2, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $driver = new Driver();
            $status = new DriverStatus();
            $status->setId($entry['driver_status_id']);
            $status->setName($entry['status_name']);
            $driver->setId($entry['id']);
            $driver->setName($entry['driver_name']);
            $driver->setStatus($status);
            $drivers[$driver->getId()] = $driver;
            //array_push($drivers, $driver);
        }
        return $drivers;
    }

    public function getEntityWithAgregatesById($id) 
    {
        $sql = "SELECT d.id id, d.name driver_name, driver_status_id, s.name status_name FROM drivers d "
                . "JOIN driver_statuses s ON driver_status_id = s.id WHERE d.id = ?";
        
        $args = array($id);
        $stmt = $this->db->run($sql, $args);
        $entry = $stmt->fetch();
        
        $driver = new Driver();
        $status = new DriverStatus();
        $status->setId($entry['driver_status_id']);
        $status->setName($entry['status_name']);
        $driver->setId($entry['id']);
        $driver->setName($entry['driver_name']);
        $driver->setStatus($status);
        
        return $driver;
    }
    
    public function getDriverByConditions($statusId = null, $limit = 100, $offset = 0)
    {
        $where = array();
        if (is_numeric($statusId)){
            array_push($where, array('driver_status_id', '=', $statusId));
        }
        
        $drivers = $this->getEntriesWithWhere($where, null, $limit, $offset);
        
        $provider = new RepositoryProvider();
        $statusRepos = $provider->getDriverStatusRepository();
        $statuses = $statusRepos->getEntries();
        
        foreach (array_values($drivers) as $driver){
            $driver->setStatus($statuses[$driver->getStatusId()]);
        }
        
        return $drivers;
    }
}
