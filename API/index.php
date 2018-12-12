<?php

namespace Kenguru\Logistic\API;

use Kenguru\Logistic\API\Router;

require_once '../modules/vendor/autoload.php';
//require_once 'autoload.php';
require_once 'Router.php';
require_once 'DAL/Repositories/RepositoryProvider.php';
require_once 'DAL/Exceptions/DbConfigException.php';
require_once 'DAL/Database/Query.php';
require_once 'DAL/Database/SQLDatabase.php';
require_once 'DAL/Entities/DbEntity.php';
require_once 'DAL/Entities/DeviceLocation.php';
require_once 'DAL/Entities/Device.php';
require_once 'DAL/Entities/DeviceModel.php';
require_once 'DAL/Entities/DeviceStatus.php';
require_once 'DAL/Entities/Driver.php';
require_once 'DAL/Entities/DriverRegistration.php';
require_once 'DAL/Entities/DriverStatus.php';
require_once 'DAL/Entities/JobStatus.php';
require_once 'DAL/Entities/RouteEvent.php';
require_once 'DAL/Entities/RouteEventType.php';
require_once 'DAL/Entities/RouteJob.php';
require_once 'DAL/Entities/Vehicle.php';
require_once 'DAL/Entities/VehicleModel.php';
require_once 'DAL/Entities/VehicleStatus.php';
require_once 'DAL/Entities/Waypoint.php';
require_once 'DAL/Entities/WaypointStatus.php';
require_once 'DAL/Entities/WaypointType.php';

require_once 'DAL/Repositories/Interfaces/IAgregateEntityRepository.php';
require_once 'DAL/Repositories/Interfaces/IRepository.php';
require_once 'DAL/Repositories/Interfaces/IDeviceLocationRepository.php';
require_once 'DAL/Repositories/Interfaces/IDeviceRepository.php';
require_once 'DAL/Repositories/Interfaces/IDeviceModelRepository.php';
require_once 'DAL/Repositories/Interfaces/IDeviceStatusRepository.php';
require_once 'DAL/Repositories/Interfaces/IDriverRepository.php';
require_once 'DAL/Repositories/Interfaces/IDriverRegistrationRepository.php';
require_once 'DAL/Repositories/Interfaces/IDriverStatusRepository.php';
require_once 'DAL/Repositories/Interfaces/IJobStatusRepository.php';
require_once 'DAL/Repositories/Interfaces/IRouteEventRepository.php';
require_once 'DAL/Repositories/Interfaces/IRouteEventTypeRepository.php';
require_once 'DAL/Repositories/Interfaces/IRouteJobRepository.php';
require_once 'DAL/Repositories/Interfaces/IVehicleRepository.php';
require_once 'DAL/Repositories/Interfaces/IVehicleModelRepository.php';
require_once 'DAL/Repositories/Interfaces/IVehicleStatusRepository.php';
require_once 'DAL/Repositories/Interfaces/IWaypointRepository.php';
require_once 'DAL/Repositories/Interfaces/IWaypointStatusRepository.php';
require_once 'DAL/Repositories/Interfaces/IWaypointTypeRepository.php';

require_once 'DAL/Repositories/SqlImplementations/SQLRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLDeviceLocationRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLDeviceRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLDeviceModelRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLDeviceStatusRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLDriverRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLDriverRegistrationRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLDriverStatusRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLJobStatusRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLRouteEventRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLRouteEventTypeRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLRouteJobRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLVehicleRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLVehicleModelRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLVehicleStatusRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLWaypointRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLWaypointStatusRepository.php';
require_once 'DAL/Repositories/SqlImplementations/SQLWaypointTypeRepository.php';

$router = Router::getInstance();
$router->run();

//$a = ['a'=>'b'];
//$repos = new DAL\Repositories\RepositoryProvider();
//$s = new DAL\Entities\DeviceModel();
//$s = $repos->getDeviceLocationRepository();
//echo print_r($a);//json_encode($a);
