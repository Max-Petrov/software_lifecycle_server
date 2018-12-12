<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IVehicleRepository;
use Kenguru\Logistic\API\DAL\Entities\Vehicle;
use Kenguru\Logistic\API\DAL\Entities\VehicleModel;
use Kenguru\Logistic\API\DAL\Entities\VehicleStatus;
use Kenguru\Logistic\API\DAL\Repositories\RepositoryProvider;

/**
 * Description of SQLVehicleRepository
 *
 * @author maxim
 */
class SQLVehicleRepository extends SQLRepository implements IVehicleRepository
{
    public function __construct() 
    {
        parent::__construct("vehicles", Vehicle::class);
    }

    public function getEntitiesWithAgregates($limit = 100, $offset = 0) 
    {
        $sql = "SELECT v.id id, number, m.id vehicle_model_id, m.name model_name, carrying, s.id vehicle_status_id, s.name status_name "
                . "FROM vehicles v "
                . "JOIN vehicle_models m ON v.vehicle_model_id = m.id "
                . "JOIN vehicle_statuses s ON v.vehicle_status_id = s.id LIMIT ?, ?";
        
        $vehicles = array();
        $bindParams = array(
            array(1, $offset, \PDO::PARAM_INT),
            array(2, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $vehicle = new Vehicle();
            $model = new VehicleModel();
            $status = new VehicleStatus();
            $model->setId($entry['vehicle_model_id']);
            $model->setName($entry['model_name']);
            $model->setCarrying($entry['carrying']);
            $status->setId($entry['vehicle_status_id']);
            $status->setName($entry['status_name']);
            $vehicle->setId($entry['id']);
            $vehicle->setNumber($entry['number']);
            $vehicle->setModel($model);
            $vehicle->setStatus($status);
            $vehicles[$vehicle->getId()] = $vehicle;
            //array_push($vehicles, $vehicle);
        }
        return $vehicles;
    }

    public function getEntityWithAgregatesById($id) 
    {
        $sql = "SELECT v.id id, number, m.id vehicle_model_id, m.name model_name, carrying, s.id vehicle_status_id, s.name status_name "
                . "FROM vehicles v "
                . "JOIN vehicle_models m ON v.vehicle_model_id = m.id "
                . "JOIN vehicle_statuses s ON v.vehicle_status_id = s.id WHERE v.id = ?";
        
        $args = array($id);
        $stmt = $this->db->run($sql, $args);
        $entry = $stmt->fetch();
        
        $vehicle = new Vehicle();
        $model = new VehicleModel();
        $status = new VehicleStatus();
        $model->setId($entry['vehicle_model_id']);
        $model->setName($entry['model_name']);
        $model->setCarrying($entry['carrying']);
        $status->setId($entry['vehicle_status_id']);
        $status->setName($entry['status_name']);
        $vehicle->setId($entry['id']);
        $vehicle->setNumber($entry['number']);
        $vehicle->setModel($model);
        $vehicle->setStatus($status);
        
        return $vehicle;
    }

    public function getVehiclesByConditions($modelId = null, $statusId = null, $number = null, $limit = 100, $offset = 0)
    {
        $where = array();
        if (is_numeric($modelId)){
            array_push($where, array('vehicle_model_id', '=', $modelId));
        }
        if (is_numeric($statusId)){
            array_push($where, array('vehicle_status_id', '=', $statusId));
        }
        if (is_string($number)){
            array_push($where, array('number', '=', $number));
        }
        
        $vehicles = $this->getEntriesWithWhere($where, null, $limit, $offset);
        
        $provider = new RepositoryProvider();
        $modelRepos = $provider->getVehicleModelRepository();
        $models = $modelRepos->getEntries();
        $statusRepos = $provider->getVehicleStatusRepository();
        $statuses = $statusRepos->getEntries();
        
        foreach (array_values($vehicles) as $vehicle){
            $vehicle->setModel($models[$vehicle->getModelId()]);
            $vehicle->setStatus($statuses[$vehicle->getStatusId()]);
        }
        
        return $vehicles;
    }
}
