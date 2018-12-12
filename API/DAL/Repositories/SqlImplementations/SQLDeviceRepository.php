<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceRepository;
use Kenguru\Logistic\API\DAL\Entities\Device;
use Kenguru\Logistic\API\DAL\Entities\DeviceModel;
use Kenguru\Logistic\API\DAL\Entities\DeviceStatus;
use Kenguru\Logistic\API\DAL\Repositories\RepositoryProvider;

/**
 * Description of SQLDeviceRepository
 *
 * @author maxim
 */
class SQLDeviceRepository extends SQLRepository implements IDeviceRepository
{
    public function __construct() 
    {
        parent::__construct("devices", Device::class);
    }

    public function getEntitiesWithAgregates($limit = 100, $offset = 0) 
    {
        $sql = "SELECT d.id id, phone_number, device_model_id, m.name model_name, device_status_id, s.name status_name "
                . "FROM `devices` d "
                . "JOIN `device_models` m ON d.device_model_id = m.id "
                . "JOIN `device_statuses` s ON d.device_status_id = s.id LIMIT ?, ?";
        
        $devices = array();
        $bindParams = array(
            array(1, $offset, \PDO::PARAM_INT),
            array(2, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $device = new Device();
            $model = new DeviceModel();
            $status = new DeviceStatus();
            $model->setId($entry['device_model_id']);
            $model->setName($entry['model_name']);
            $status->setId($entry['device_status_id']);
            $status->setName($entry['status_name']);
            $device->setId($entry['id']);
            $device->setPhoneNumber($entry['phone_number']);
            $device->setModel($model);
            $device->setStatus($status);
            $devices[$device->getId()] = $device;
            //array_push($devices, $device);
        }
        return $devices;
    }

    public function getEntityWithAgregatesById($id) 
    {
        $sql = "SELECT d.id id, phone_number, device_model_id, m.name model_name, device_status_id, s.name status_name "
                . "FROM devices d "
                . "JOIN device_models m ON d.device_model_id = m.id "
                . "JOIN device_statuses s ON d.device_status_id = s.id WHERE d.id = ?";
        
        $args = array($id);
        $stmt = $this->db->run($sql, $args);
        $entry = $stmt->fetch();
        
        $device = new Device();
        $model = new DeviceModel();
        $status = new DeviceStatus();
        $model->setId($entry['device_model_id']);
        $model->setName($entry['model_name']);
        $status->setId($entry['device_status_id']);
        $status->setName($entry['status_name']);
        $device->setId($entry['id']);
        $device->setPhoneNumber($entry['phone_number']);
        $device->setModel($model);
        $device->setStatus($status);
        
        return $device;
    }
    
    public function getDevicesByConditions($modelId = null, $statusId = null, $phoneNumber = null, $limit = 100, $offset = 0)
    {
        $where = array();
        if (is_numeric($modelId)){
            array_push($where, array('device_model_id', '=', $modelId));
        }
        if (is_numeric($statusId)){
            array_push($where, array('device_status_id', '=', $statusId));
        }
        if (is_numeric($phoneNumber)){
            array_push($where, array('phone_number', '=', $phoneNumber));
        }
        
        $devices = $this->getEntriesWithWhere($where, null, $limit, $offset);
        
        $provider = new RepositoryProvider();
        $modelRepos = $provider->getDeviceModelRepository();
        $models = $modelRepos->getEntries();
        $statusRepos = $provider->getDeviceStatusRepository();
        $statuses = $statusRepos->getEntries();
        
        foreach (array_values($devices) as $device){
            $device->setModel($models[$device->getModelId()]);
            $device->setStatus($statuses[$device->getStatusId()]);
        }
        
        return $devices;
    }
}
