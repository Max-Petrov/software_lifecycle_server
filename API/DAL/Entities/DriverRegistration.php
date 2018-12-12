<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Description of DriverRegistration
 *
 * @author maxim
 */
class DriverRegistration extends DbEntity
{
    private $actual;
    private $timeStart;
    private $timeFinish;
    private $driverId;
    private $vehicleId;
    private $deviceId;
    private $driver;
    private $vehicle;
    private $device;
            
    function __construct() 
    {
        $this->actual = NULL;
        $this->timeStart = NULL;
        $this->timeFinish = NULL;
        $this->driverId = NULL;
        $this->vehicleId = NULL;
        $this->deviceId = NULL;
        $this->driver = NULL;
        $this->vehicle = NULL;
        $this->device = NULL;
        parent::__construct();
    }
    
    function getActual() 
    {
        return $this->actual;
    }

    function getTimeStart() 
    {
        return $this->timeStart;
    }

    function getTimeFinish() 
    {
        return $this->timeFinish;
    }

    function getDriverId() 
    {
        return $this->driverId;
    }

    function getVehicleId()
    {
        return $this->vehicleId;
    }

    function getDeviceId() 
    {
        return $this->deviceId;
    }

    function getDriver() 
    {
        return $this->driver;
    }

    function getVehicle()
    {
        return $this->vehicle;
    }

    function getDevice() 
    {
        return $this->device;
    }

    function setActual($actual) 
    {
        $this->actual = $actual;
    }

    function setTimeStart($timeStart) 
    {
        $this->timeStart = $timeStart;
    }

    function setTimeFinish($timeFinish) 
    {
        $this->timeFinish = $timeFinish;
    }

    function setDriverId($driverId) 
    {
        $this->driverId = $driverId;
    }

    function setVehicleId($vehicleId) 
    {
        $this->vehicleId = $vehicleId;
    }

    function setDeviceId($deviceId) 
    {
        $this->deviceId = $deviceId;
    }

    function setDriver(Driver $driver) 
    {
        $this->driver = $driver;
        $this->driverId = $driver->getId();
    }

    function setVehicle(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->vehicleId = $vehicle->getId();
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
            'actual' => $this->actual,
            'time_start' => $this->timeStart,
            'time_finish' => $this->timeFinish,
            'driver_id' => $this->driverId,
            'vehicle_id' => $this->vehicleId,
            'device_id' => $this->deviceId,
            'driver' => $this->driver,
            'vehicle' => $this->vehicle,
            'device' => $this->device
        );
        return $result;
    }

    public function setData($values) 
    {
        $this->id = $values['id'];
        $this->actual = $values['actual'];
        $this->timeStart = $values['time_start'];
        $this->timeFinish = $values['time_finish'];
        $this->driverId = $values['driver_id'];
        $this->vehicleId = $values['vehicle_id'];
        $this->deviceId = $values['device_id'];
        $this->driver = $values['driver'];
        $this->vehicle = $values['vehicle'];
        $this->device = $values['device'];
    }

    public function getDbSetData() 
    {
        $result = array(
            'id' => $this->id,
            'actual' => $this->actual,
            'time_start' => $this->timeStart,
            'time_finish' => $this->timeFinish,
            'driver_id' => $this->driverId,
            'vehicle_id' => $this->vehicleId,
            'device_id' => $this->deviceId
        );
        return $result;
    }

}
