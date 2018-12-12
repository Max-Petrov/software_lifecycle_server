<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of deviceLocation
 *
 * @author maxim
 */
class DeviceLocation extends DbEntity
{
    private $locationTime;
    private $latitude;
    private $longitude;
    private $deviceId;
    private $device;
    
    function __construct() 
    {
        $this->locationTime = NULL;
        $this->latitude = NULL;
        $this->longitude = NULL;
        $this->deviceId = NULL;
        $this->device = NULL;
        parent::__construct();
    }

    function getLocationTime() 
    {
        return $this->locationTime;
    }

    function getLatitude() 
    {
        return $this->latitude;
    }

    function getLongitude() 
    {
        return $this->longitude;
    }

    function getDeviceId() 
    {
        return $this->deviceId;
    }

    function getDevice() 
    {
        return $this->device;
    }

    function setLocationTime($locationTime) 
    {
        $this->locationTime = $locationTime;
    }

    function setLatitude($latitude) 
    {
        $this->latitude = $latitude;
    }

    function setLongitude($longitude) 
    {
        $this->longitude = $longitude;
    }

    function setDeviceId($deviceId) 
    {
        $this->deviceId = $deviceId;
    }

    function setDevice(Device $device) 
    {
        $this->device = $device;
        $this->deviceId = $device->getId();
    }

    public function getData() 
    {
        $result = array(
            'id' => $this->id,
            'location_time' => $this->locationTime,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'device_id' => $this->deviceId,
            'device' => $this->device
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->locationTime = $values['location_time'];
        $this->latitude = $values['latitude'];
        $this->longitude = $values['longitude'];
        $this->deviceId = $values['device_id'];
        $this->device = $values['device'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'location_time' => $this->locationTime,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'device_id' => $this->deviceId
        );
        return $result;
    }

}
