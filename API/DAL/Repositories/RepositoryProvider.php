<?php

namespace Kenguru\Logistic\API\DAL\Repositories;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceLocationRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceModelRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDeviceStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDriverRegistrationRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDriverRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDriverStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IJobStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IRouteEventRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IRouteEventTypeRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IRouteJobRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IVehicleModelRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IVehicleRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IVehicleStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IWaypointRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IWaypointTypeRepository;
use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IWaypointStatusRepository;

use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDeviceLocationRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDeviceModelRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDeviceRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDeviceStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDriverRegistrationRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDriverRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDriverStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLJobStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLRouteEventRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLRouteEventTypeRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLRouteJobRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLVehicleModelRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLVehicleRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLVehicleStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLWaypointRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLWaypointTypeRepository;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLWaypointStatusRepository;

/**
 * Description of RepositoryProvider
 *
 * @author maxim
 */
class RepositoryProvider 
{
    public function __construct() 
    {
        ;
    }
    
    public function getDeviceLocationRepository() : IDeviceLocationRepository
    {
        return new SQLDeviceLocationRepository;
    }
    
    public function getDeviceModelRepository() : IDeviceModelRepository
    {
        return new SQLDeviceModelRepository;
    }
    
    public function getDeviceStatusRepository() : IDeviceStatusRepository
    {
        return new SQLDeviceStatusRepository;
    }
    
    public function getDeviceRepository() : IDeviceRepository
    {
        return new SQLDeviceRepository;
    }
    
    public function getDriverRegistrationRepository() : IDriverRegistrationRepository
    {
        return new SQLDriverRegistrationRepository;
    }
    
    public function getDriverRepository() : IDriverRepository
    {
        return new SQLDriverRepository;
    }
    
    public function getDriverStatusRepository() : IDriverStatusRepository
    {
        return new SQLDriverStatusRepository;
    }
    
    public function getJobStatusRepository() : IJobStatusRepository
    {
        return new SQLJobStatusRepository;
    }
    
    public function getRouteEventRepository() : IRouteEventRepository
    {
        return new SQLRouteEventRepository;
    }
    
    public function getRouteEventTypeRepository() : IRouteEventTypeRepository
    {
        return new SQLRouteEventTypeRepository;
    }
    
    public function getRouteJobRepository() : IRouteJobRepository
    {
        return new SQLRouteJobRepository;
    }
    
    public function getVehicleModelRepository() : IVehicleModelRepository
    {
        return new SQLVehicleModelRepository;
    }
    
    public function getVehicleRepository() : IVehicleRepository
    {
        return new SQLVehicleRepository;
    }
    
    public function getVehicleStatusRepository() : IVehicleStatusRepository
    {
        return new SQLVehicleStatusRepository;
    }
    
    public function getWaypointRepository() : IWaypointRepository
    {
        return new SQLWaypointRepository;
    }
    
    public function getWaypointTypeRepository() : IWaypointTypeRepository
    {
        return new SQLWaypointTypeRepository;
    }
    
    public function getWaypointStatusRepository() : IWaypointStatusRepository
    {
        return new SQLWaypointStatusRepository;
    }
}
