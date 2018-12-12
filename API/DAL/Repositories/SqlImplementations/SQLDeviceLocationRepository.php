<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceLocationRepository;
use Kenguru\Logistic\API\DAL\Entities\DeviceLocation;
use Kenguru\Logistic\API\DAL\Database\Query;

/**
 * Description of SQLDeviceLocationRepository
 *
 * @author maxim
 */
class SQLDeviceLocationRepository extends SQLRepository implements IDeviceLocationRepository
{
    public function __construct() 
    {
        parent::__construct("device_locations", DeviceLocation::class);
    }

    public function getDeviceLocationsByDevice($deviceId, $limit = 100, $offset = 0)
    {
        $query = new Query($this->tableName, $limit, $offset);
        $query->setWhere(array(array('device_id', '=', $deviceId)));
        $stmt = $this->db->select($query);
        $entries = $stmt->fetchAll();
        $locations = array();
        foreach ($entries as $entry){
            $location = new DeviceLocation();
            $location->setData($entry);
            $locations[$locations->getId()] = $locations;
            //array_push($locations, $location);
        }
        
        return $locations;
    }

    public function getLastDeviceLocationByDevice($deviceId) 
    {
        $query = new Query($this->tableName, 1, 0);
        $query->setWhere(array(array('device_id', '=', $deviceId)));
        $query->setOrderBy(array('location_time' => 'DESC'));
        $stmt = $this->db->select($query);
        $entry = $stmt->fetch();
        
        $location = new DeviceLocation();
        $location->setData($entry);
        
        return $location;
    }

    public function getLocationsByConditions($deviceId = null, $periodStart = null, $periodFinish = null, $limit = 100, $offset = 0)
    {
        $where = array();
        if (is_numeric($deviceId)){
            array_push($where, array('device_id', '=', $deviceId));
        }
        if (is_string($periodStart)){
            array_push($where, array('location_time', '>=', $periodStart));
        }
        if (is_string($periodFinish)){
            array_push($where, array('location_time', '<=', $periodFinish));
        }
        
        $locations = $this->getEntriesWithWhere($where, null, $limit, $offset);
        
        return $locations;
    }
}
