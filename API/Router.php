<?php

namespace Kenguru\Logistic\API;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Kenguru\Logistic\API\DAL\Entities\DbEntity;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLJobStatusRepository;
use Kenguru\Logistic\API\DAL\Repositories\RepositoryProvider;
use Kenguru\Logistic\API\DAL\Repositories\SQLImplementations\SQLDriverRepository;
use Kenguru\Logistic\API\DAL\Entities\JobStatus;
use Kenguru\Logistic\API\DAL\Entities\Driver;
use Kenguru\Logistic\API\DAL\Entities\RouteEvent;
use Kenguru\Logistic\API\DAL\Entities\Vehicle;
use Kenguru\Logistic\API\DAL\Entities\RouteJob;
use Kenguru\Logistic\API\DAL\Entities\Waypoint;
use Kenguru\Logistic\API\DAL\Entities\DeviceLocation;
use Kenguru\Logistic\API\DAL\Entities\DriverRegistration;

/**
 * Description of Router
 *
 * @author maxim
 */
class Router
{
    private static $instance = null;

    private $app = null;
    
    private $repositoryProvider;

    private function __wakeup() {}
    private function __clone() {}

    private function __construct()
    {
        $config = ['settings' => [
                        'displayErrorDetails' => true
                    ]];
        $this->app = new \Slim\App($config);
        $this->repositoryProvider = new RepositoryProvider();
        
        //$this->registerTestRoutes($this->repositoryProvider);
        $this->registerDevicesRoutes($this->repositoryProvider);
        $this->registerDeviceLocationsRoutes($this->repositoryProvider);
        $this->registerDriversRoutes($this->repositoryProvider);
        $this->registerDriverRegistrationsRoutes($this->repositoryProvider);
        $this->registerRouteEventsRoutes($this->repositoryProvider);
        $this->registerVehiclesRoutes($this->repositoryProvider);
        $this->registerRouteJobsRoutes($this->repositoryProvider);
        $this->registerWaypointsRoutes($this->repositoryProvider);
        $this->registerWaypointTypesRoutes($this->repositoryProvider);
        $this->registerJobStatusesRoutes($this->repositoryProvider); 
    }
    
    public static function getInstance()
    {
        if (is_null(self::$instance)){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::getInstance(), $method), $args);
    }
    
    public function run()
    {
        $this->app->run();
    }
    
    private function registerTestRoutes(RepositoryProvider $repositoryProvider)
    {   
        $this->app->get('/test', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $plan = new \DateTime('2018-03-22 7:30:00');
            $fact = new \DateTime('2018-03-23 6:14:00');
            $interval = $fact->diff($plan);
            $dir = $interval->invert == 1? '+' : '-';
            $time = $interval->d * 24 * 60 + $interval->h * 60 + $interval->i;
            $dif = "Отставание: $dir $time минут, $interval->d дней $interval->h часов $interval->i минут";
            $response->getBody()->write($dif);
            return $response->withHeader('Content-Type', 'text/html');
        });
    }
    
    private function registerDevicesRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/devices/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDeviceRepository();
            $device = $repos->getEntityWithAgregatesById($args['id']);
            $json = json_encode($device);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/devices', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDeviceRepository();
            $params = $request->getQueryParams();
            
            $modelId = $params['model_id'];
            $statusId = $params['status_id'];
            $phoneNumber = $params['phone_number'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            $register = $params['register'];
            
            if (is_null($limit)){
                $limit = 100;
            }
            if (is_null($offset)){
                $offset = 0;
            }
            
            $devices = $repos->getDevicesByConditions($modelId, $statusId, $phoneNumber, $limit, $offset);
            
            if ($register == 'true' || $register == 'false'){
                $regRepos = $repositoryProvider->getDriverRegistrationRepository();
                $regs = $regRepos->getDriverRegistrationsByConditions(1);
                
                $buf = array();

                foreach ($devices as $k => $v) {
                    $buf[$k] = clone $v;
                }
                foreach (array_values($regs) as $reg){
                    unset($buf[$reg->getDeviceId()]);
                }
                
                if ($register == 'false'){
                    $devices = $buf;
                }
                else {
                    foreach (array_keys($buf) as $id){
                        unset($devices[$id]);
                    }
                }
            }
            
            $json = json_encode(array_values($devices));
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
    }
    
    private function registerDeviceLocationsRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/device_locations/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDeviceLocationRepository();
            $result = $repos->getEntryById($args['id']);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/device_locations', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDeviceLocationRepository();
            $params = $request->getQueryParams();
            $deviceId = $params['device_id'];
            $periodStart = $params['period_start'];
            $periodFinish = $params['period_finish'];
            $last = $params['last'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            $withRegistrations = $params['with_registrations'];
            
            if (is_null($limit)){
                $limit = 100;
            }
            if (is_null($offset)){
                $offset = 0;
            }
            
            if (is_null($last) || $last == 'false'){
                $result = array_values($repos->getLocationsByConditions($deviceId, $periodStart, $periodFinish, $limit, $offset));
            }
            else{
                $registrationRepos = $repositoryProvider->getDriverRegistrationRepository();
                $regs = array_values($registrationRepos->getDriverRegistrationsByConditions(true));
                $result = array();
                foreach ($regs as $reg){
                    array_push($result, $repos->getLastDeviceLocationByDevice($reg->getDeviceId()));
                }
                if (!is_null($withRegistrations) && $withRegistrations == 'true'){
                    foreach (array_values($result) as $loc){
                        $registr = null;
                        $driverRepos = $repositoryProvider->getDriverRepository();
                        $deviceRepos = $repositoryProvider->getDeviceRepository();
                        $vehicleRepos = $repositoryProvider->getVehicleRepository();
                        foreach ($regs as $reg){
                            if ($reg->getDeviceId() == $loc->getDeviceId()){
                                $driver = $driverRepos->getEntityWithAgregatesById($reg->getDriverId());
                                $reg->setDriver($driver);

                                $device = $deviceRepos->getEntityWithAgregatesById($reg->getDeviceId());
                                $reg->setDevice($device);

                                $vehicle = $vehicleRepos->getEntityWithAgregatesById($reg->getVehicleId());
                                $reg->setVehicle($vehicle);
                                
                                $registr = $reg;
                                break;
                            }
                        }
                        $loc->includeData($registr, 'driver_registration');
                    }
                }
            }
            
            $json = json_encode(array_values($result));
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
        
        $this->app->post('/device_locations', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $params = $request->getQueryParams();
            $deviceId = $params['device_id'];
            $isList = $params['list'];
            if (is_null($deviceId)) {
                $result = 0;
            }
            else {
                $repos = $repositoryProvider->getDeviceLocationRepository();
                $locationData = json_decode($request->getBody(), TRUE);
                if (!is_null($isList) && $isList == 'true') {
                    $locations = array();
                    foreach ($locationData as $location) {
                        $entity = new DeviceLocation();
                        $entity->setData($location);
                        $entity->setDeviceId($deviceId);
                        array_push($locations, $entity);
                    }
                    $result = $repos->insertEntries($locations);
                } else {
                    $location = new DeviceLocation();
                    $location->setData($locationData);
                    $location->setDeviceId($deviceId);
                    $result = $repos->insertEntry($location);
                }
            }
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->delete('/device_locations/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getDeviceLocationRepository();
            $result = $repos->deleteEntry($args['id']);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
        
        $this->app->put('/device_locations/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDeviceLocationRepository();
            $locationData = json_decode($request->getBody(), TRUE);
            $location = new DeviceLocation();
            $location->setData($locationData);
            $location->setId($args['id']);
            $result = $repos->updateEntry($location);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
    }
    
    private function registerDriversRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/drivers/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDriverRepository();
            $result = $repos->getEntityWithAgregatesById($args['id']);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/drivers', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDriverRepository();
            $params = $request->getQueryParams();
            
            $statusId = $params['status_id'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            $register = $params['register'];
            
            if (is_null($limit)){
                $limit = 100;
            }
            if (is_null($offset)){
                $offset = 0;
            }
            
            $drivers = $repos->getDriverByConditions($statusId, $limit, $offset);
            
            if ($register == 'true' || $register == 'false'){
                $regRepos = $repositoryProvider->getDriverRegistrationRepository();
                $regs = $regRepos->getDriverRegistrationsByConditions(1);
                
                $buf = array();

                foreach ($drivers as $k => $v) {
                    $buf[$k] = clone $v;
                }
                foreach (array_values($regs) as $reg){
                    unset($buf[$reg->getDriverId()]);
                }
                
                if ($register == 'false'){
                    $drivers = $buf;
                }
                else {
                    foreach (array_keys($buf) as $id){
                        unset($drivers[$id]);
                    }
                }
            }
            
            $json = json_encode(array_values($drivers));
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
        
        $this->app->post('/drivers', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getDriverRepository();
            $driverData = json_decode($request->getBody(), TRUE);
            $driver = new Driver();
            $driver->setData($driverData);
            $result = $repos->insertEntry($driver);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->delete('/drivers/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getDriverRepository();
            $result = $repos->deleteEntry($args['id']);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
        
        $this->app->put('/drivers/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDriverRepository();
            $driverData = json_decode($request->getBody(), TRUE);
            $driver = new Driver();
            $driver->setData($driverData);
            $driver->setId($args['id']);
            $result = $repos->updateEntry($driver);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
    }
    
    private function registerDriverRegistrationsRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/driver_registrations/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDriverRegistrationRepository();
            
            $params = $request->getQueryParams();
            $withDriver = $params['with_driver'];
            $withDevice = $params['with_device'];
            $withVehicle = $params['with_vehicle'];
            
            $result = $repos->getEntryById($args['id']);
            
            if (!is_null($withDriver) && $withDriver == 'true'){
                $driverRepos = $repositoryProvider->getDriverRepository();
                $driver = $driverRepos->getEntityWithAgregatesById($result->getDriverId());
                $result->setDriver($driver);
            }
            if (!is_null($withDevice) && $withDevice == 'true'){
                $deviceRepos = $repositoryProvider->getDeviceRepository();
                $device = $deviceRepos->getEntityWithAgregatesById($result->getDeviceId());
                $result->setDevice($device);
            }
            if (!is_null($withVehicle) && $withVehicle == 'true'){
                $vehicleRepos = $repositoryProvider->getVehicleRepository();
                $vehicle = $vehicleRepos->getEntityWithAgregatesById($result->getVehicleId());
                $result->setVehicle($vehicle);
            }
            
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/driver_registrations', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDriverRegistrationRepository();
            $params = $request->getQueryParams();
                    
            $actualParam = $params['actual'];
            $periodStart = $params['period_start'];
            $periodFinish = $params['period_finish'];
            $driverId = $params['driver_id'];
            $deviceId = $params['device_id'];
            $vehicleId = $params['vehicle_id'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            
            $withDriver = $params['with_driver'];
            $withDevice = $params['with_device'];
            $withVehicle = $params['with_vehicle'];
            
            if (is_null($limit)){
                $limit = 100;
            }
            if (is_null($offset)){
                $offset = 0;
            }
            
            if ($actualParam != null){
                $actual = ($actualParam == 'true' || $actualParam == 1) ? 1 : 0;
            }
            $result = $repos->getDriverRegistrationsByConditions($actual, $periodStart, $periodFinish, $driverId, $deviceId, $vehicleId, $limit, $offset);
            
            $result = array_values($result);
            
            $drivers = null;
            $devices = null;
            $vehicles = null;
            
            if (!is_null($withDriver) && $withDriver == 'true'){
                $driverRepos = $repositoryProvider->getDriverRepository();
                $drivers = $driverRepos->getEntitiesWithAgregates();
            }
            if (!is_null($withDevice) && $withDevice == 'true'){
                $deviceRepos = $repositoryProvider->getDeviceRepository();
                $devices = $deviceRepos->getEntitiesWithAgregates();
            }
            if (!is_null($withVehicle) && $withVehicle == 'true'){
                $vehicleRepos = $repositoryProvider->getVehicleRepository();
                $vehicles = $vehicleRepos->getEntitiesWithAgregates();
            }
            
            foreach ($result as $registration){
                if (is_array($drivers)){
                    $registration->setDriver($drivers[$registration->getDriverId()]);
                }
                if (is_array($devices)){
                    $registration->setDevice($devices[$registration->getDeviceId()]);
                }
                if (is_array($vehicles)){
                    $registration->setVehicle($vehicles[$registration->getVehicleId()]);
                }
            }
            
            $json = json_encode($result);
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
        
        $this->app->post('/driver_registrations', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getDriverRegistrationRepository();
            $driverRegistrationData = json_decode($request->getBody(), TRUE);
            $driverRegistration = new DriverRegistration();
            $driverRegistration->setData($driverRegistrationData);
            $result = $repos->insertEntry($driverRegistration);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->delete('/driver_registrations/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getDriverRegistrationRepository();
            $result = $repos->deleteEntry($args['id']);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
        
        $this->app->put('/driver_registrations/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getDriverRegistrationRepository();
            $driverRegistrationData = json_decode($request->getBody(), TRUE);
            $driverRegistration = new DriverRegistration();
            $driverRegistration->setData($driverRegistrationData);
            $driverRegistration->setId($args['id']);
            $result = $repos->updateEntry($driverRegistration);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
    }
    
    private function registerRouteEventsRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/route_events/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getRouteEventRepository();
            
            $params = $request->getQueryParams();
            $withWaypoint = $params['with_waypoint'];
            
            $result = $repos->getEntityWithAgregatesById($args['id']);
            
            if (!is_null($withWaypoint) && $withWaypoint == true){
                $waypointRepos = $repositoryProvider->getWaypointRepository();
                $waypoint = $waypointRepos->getEntityWithAgregatesById($result->getWaypointId());
                $result->setWaypoint($waypoint);
            }
            
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/route_events', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getRouteEventRepository();
            $params = $request->getQueryParams();
                    
            $waypointId = $params['waypoint_id'];
            $routeEventTypeId = $params['event_type_id'];
            $periodStart = $params['start'];
            $periodFinish = $params['finish'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            $withWaypoints = $params['with_waypoints'];
            
            
            if (is_null($limit)){
                $limit = 100;
            }
            if (is_null($offset)){
                $offset = 0;
            }
            
            $result = array_values($repos->getRouteEventsByConditions($waypointId, $routeEventTypeId, $periodStart, $periodFinish, $limit, $offset));
            
            if (!is_null($withWaypoints) && $withWaypoints == 'true'){
                $waypointRepos = $repositoryProvider->getWaypointRepository();
                foreach ($result as $event){
                    $waypoint = $waypointRepos->getEntryById($event->getWaypointId());
                    $event->setWaypoint($waypoint);
                }
            }
            
//            $result = array_values($result);
            
            $json = json_encode($result);
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
        
        $this->app->post('/route_events', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $params = $request->getQueryParams();
            $deviceId = $params['device_id'];
            $isList = $params['list'];
            if (is_null($deviceId)) {
                $result = 0;
            }
            else {
                $regRepos = $repositoryProvider->getDriverRegistrationRepository();
                $registrations = $regRepos->getDriverRegistrationsByConditions(1, null, null, null, $deviceId, null, 1);
                $routeRepos = $repositoryProvider->getRouteJobRepository();
                $route = $routeRepos->getCurrentRouteJobByRegistrationId(array_values($registrations)[0]->getId());
                $repos = $repositoryProvider->getRouteEventRepository();
                $eventData = json_decode($request->getBody(), TRUE);
                if (!is_null($isList) && $isList == 'true') {
                    $events = array();
                    foreach ($eventData as $event) {
                        $entity = new RouteEvent();
                        $entity->setData($event);
                        array_push($events, $entity);
                    }
                    $result = $repos->insertEntries($events);
                } else {
                    $event = new RouteEvent();
                    $event->setData($eventData);
                    switch ($event->getRouteEventTypeId()) {
                        case 1:
                            $route->setStartTimePlan($event->getEventTime());
                            $route->setStartTimeFact($event->getEventTime());
                            $route->setStatusId(3);
                            $routeRepos->updateEntry($route);
                            break;
                        
                        case 2:
                            $route->setFinishTimeFact($event->getEventTime());
                            $plan = new \DateTime($route->getFinishTimePlan());
                            $fact = new \DateTime($route->getFinishTimeFact());
                            $interval = $fact->diff($plan);
                            $dif = $interval->d * 24 * 60 + $interval->h * 60 + $interval->i;
                            if ($interval->invert == 1 || ($dif <= 60 && $interval->invert == 0)) {
                                $route->setStatusId(5);
                            }
                            else {
                                $route->setStatusId(4);
                            }
                            $routeRepos->updateEntry($route);
                            break;
                        
                        case 3:
                            $waypointRepos = $repositoryProvider->getWaypointRepository();
                            $waypoint = $waypointRepos->getEntryById($event->getWaypointId());
                            $waypoint->setArriveTimeFact($event->getEventTime());
                            $waypointRepos->updateEntry($waypoint);
                            break;
                        
                        case 4:
                            $waypointRepos = $repositoryProvider->getWaypointRepository();
                            $waypoint = $waypointRepos->getEntryById($event->getWaypointId());
                            $waypoint->setLeaveTimeFact($event->getEventTime());
                            
                            $plan = new \DateTime($waypoint->getLeaveTimePlan());
                            $fact = new \DateTime($waypoint->getLeaveTimeFact());
                            $interval = $fact->diff($plan);
                            $dif = $interval->d * 24 * 60 + $interval->h * 60 + $interval->i;
                            if ($interval->invert == 1 || ($dif <= 60 && $interval->invert == 0)) {
                                $waypoint->setWaypointStatusId(4);
                            }
                            else {
                                $waypoint->setWaypointStatusId(3);
                            }
                            
                            $waypointRepos->updateEntry($waypoint);
                            break;
                        
                        case 7:

                            break;

                        default:
                            break;
                    }
                    $result = $repos->insertEntry($event);
                }
            }
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->delete('/route_events/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getRouteEventRepository();
            $result = $repos->deleteEntry($args['id']);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
        
        $this->app->put('/route_events/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getRouteEventRepository();
            $eventData = json_decode($request->getBody(), TRUE);
            $event = new RouteEvent();
            $event->setData($eventData);
            $event->setId($args['id']);
            $result = $repos->updateEntry($event);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
    }
    
    private function registerVehiclesRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/vehicles/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getVehicleRepository();
            $result = $repos->getEntityWithAgregatesById($args['id']);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/vehicles', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getVehicleRepository();
            $params = $request->getQueryParams();
                    
            $modelId = $params['model_id'];
            $statusId = $params['status_id'];
            $number = $params['number'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            $register = $params['register'];
            
            if (is_null($limit)){
                $limit = 100;
            }
            if (is_null($offset)){
                $offset = 0;
            }
            
            $vehicles = $repos->getVehiclesByConditions($modelId, $statusId, $number, $limit, $offset);
            
            if ($register == 'true' || $register == 'false'){
                $regRepos = $repositoryProvider->getDriverRegistrationRepository();
                $regs = $regRepos->getDriverRegistrationsByConditions(1);
                
                $buf = array();

                foreach ($vehicles as $k => $v) {
                    $buf[$k] = clone $v;
                }
                foreach (array_values($regs) as $reg){
                    unset($buf[$reg->getVehicleId()]);
                }
                
                if ($register == 'false'){
                    $vehicles = $buf;
                }
                else {
                    foreach (array_keys($buf) as $id){
                        unset($vehicles[$id]);
                    }
                }
            }
            
            $json = json_encode(array_values($vehicles));
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
        
        $this->app->post('/vehicles', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getVehicleRepository();
            $vehicleData = json_decode($request->getBody(), TRUE);
            $vehicle = new Vehicle();
            $vehicle->setData($vehicleData);
            $result = $repos->insertEntry($vehicle);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->delete('/vehicles/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getVehicleRepository();
            $result = $repos->deleteEntry($args['id']);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
        
        $this->app->put('/vehicles/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getVehicleRepository();
            $vehicleData = json_decode($request->getBody(), TRUE);
            $vehicle = new Vehicle();
            $vehicle->setData($vehicleData);
            $vehicle->setId($args['id']);
            $result = $repos->updateEntry($vehicle);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
    }
    
    private function registerRouteJobsRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/route_jobs/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $params = $request->getQueryParams();
            $withWaypoints = $params['with_waypoints'];
            
            $repos = $repositoryProvider->getRouteJobRepository();
            $result = $repos->getEntityWithAgregatesById($args['id']);
            if (!is_null($withWaypoints) && $withWaypoints == 'true'){
                $waypointRepos = $repositoryProvider->getWaypointRepository();
                $waypoints = array_values($waypointRepos->getWaypointsByConditions($result->getId()));
                $result->includeData($waypoints, 'waypoints');
            }
            
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/route_jobs', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getRouteJobRepository();
            $params = $request->getQueryParams();
                    
            $registrationId = $params['registration_id'];
            $statusId = $params['job_status_id'];
            $periodStart = $params['period_start'];
            $periodFinish = $params['period_finish'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            $withWaypoints = $params['with_waypoints'];
            $withRegistrations = $params['with_registrations'];
            
            /**
             * Параметр 'device_id' и 'current' предназначен только для запросов мобильного приложения
             */
            /* Начало обработки запроса с мобильного приложения */
            $deviceId = $params['device_id'];
            $current = $params['current'];
            if (!is_null($deviceId) && $current == 'true') {
                $regRepos = $repositoryProvider->getDriverRegistrationRepository();
                $registrations = $regRepos->getDriverRegistrationsByConditions(1, null, null, null, $deviceId, null, 1);
                $route = $repos->getCurrentRouteJobByRegistrationId(array_values($registrations)[0]->getId());
                if (!is_null($withWaypoints) && $withWaypoints == 'true'){
                    $waypointRepos = $repositoryProvider->getWaypointRepository();
                    $waypoints = array_values($waypointRepos->getWaypointsByConditions($route->getId()));
                    $result['route'] = $route;
                    $result['waypoints'] = $waypoints;
                    //$result->includeData($waypoints, 'waypoints');
                } else {
                    $result = $route;
                }
            /* Конец обработки запроса с мобильного приложения */
            /**
             * Часть результата была помещена в обертку для удобства мобильного приложения
             */
            } else if (is_null($deviceId) && is_null($current)){
                if (is_null($limit)){
                    $limit = 100;
                }
                if (is_null($offset)){
                    $offset = 0;
                }

                $result = $repos->getRouteJobsByConditions($registrationId, $periodStart, $periodFinish, $statusId, $limit, $offset);
                $result = array_values($result);
                
                if (!is_null($withWaypoints) && $withWaypoints == 'true'){
                    $waypointRepos = $repositoryProvider->getWaypointRepository();
                    foreach ($result as $route){
                        $waypoints = array_values($waypointRepos->getWaypointsByConditions($route->getId()));
                        $route->includeData($waypoints, 'waypoints');
                    }
                }

                if (!is_null($withRegistrations) && $withRegistrations == 'true'){
                    $regsRepos = $repositoryProvider->getDriverRegistrationRepository();
                    foreach ($result as $route){
                        $reg = $regsRepos->getEntryById($route->getDriverRegistrationId());
                        $route->setDriverRegistration($reg);
                    }
                }
            }
            
            $json = json_encode($result);
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
        
        $this->app->post('/route_jobs', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getRouteJobRepository();
            $routeData = json_decode($request->getBody(), TRUE);
            $route = new RouteJob();
            $route->setData($routeData);
            $result = $repos->insertEntry($route);
            
            $waypointSequence = $routeData['waypoints'];
            $waypointRepos = $repositoryProvider->getWaypointRepository();
            
            for ($i = 0; $i < count($waypointSequence); $i++){
                $waypointRepos->updateNumber($waypointSequence[$i], $i);
            }
            
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->delete('/route_jobs/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getRouteJobRepository();
            $result = $repos->deleteEntry($args['id']);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
        
        $this->app->put('/route_jobs/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getRouteJobRepository();
            $routeData = json_decode($request->getBody(), TRUE);
            $route = $repos->getEntryById($args['id']);
            $data = $route->getDbSetData();
            foreach ($routeData as $field => $value) {
                $data[$field] = $value;
            }
            $route->setData($data);
            $route->setId($args['id']);
            $result = $repos->updateEntry($route);
            
            $waypointSequence = $routeData['waypoints'];
            $waypointRepos = $repositoryProvider->getWaypointRepository();
            
            for ($i = 0; $i < count($waypointSequence); $i++){
                $waypointRepos->updateNumber($waypointSequence[$i], $i);
            }
            
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
    }
    
    private function registerWaypointsRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/waypoints/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getWaypointRepository();
            $result = $repos->getEntityWithAgregatesById($args['id']);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/waypoints', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getWaypointRepository();
            
            $params = $request->getQueryParams();
                    
            $routeJobId = $params['route_job_id'];
            $periodStart = $params['period_start'];
            $periodFinish = $params['period_finish'];
            $statusId = $params['waypoint_status_id'];
            $typeId = $params['waypoint_type_id'];
            $limit = $params['limit'];
            $offset = $params['offset'];
            $withRoutes = $params['with_routes'];
            
            if (is_null($limit)){
                $limit = 100;
            }
            if (is_null($offset)){
                $offset = 0;
            }
            
            $result = $repos->getWaypointsByConditions($routeJobId, $periodStart, $periodFinish, $statusId, $typeId, $limit, $offset);
            
            if (!is_null($withRoutes) && $withRoutes == 'true'){
                $jobRepos = $repositoryProvider->getRouteJobRepository();
                $regsRepos = $repositoryProvider->getDriverRegistrationRepository();
                foreach ($result as $point){
                    $route = $jobRepos->getEntryById($point->getRouteJobId());
                    $reg = $regsRepos->getEntryById($route->getDriverRegistrationId());
                    $route->setDriverRegistration($reg);
                    $point->setRouteJob($route);
                }
            }
            
            $result = array_values($result);
            
            $json = json_encode($result);
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
        
        $this->app->post('/waypoints', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getWaypointRepository();
            $waypointData = json_decode($request->getBody(), TRUE);
            $waypoint = new Waypoint();
            $waypoint->setData($waypointData);
            $id = $repos->insertEntry($waypoint);
            $result = $repos->getEntityWithAgregatesById($id['id']);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->delete('/waypoints/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {

            $repos = $repositoryProvider->getWaypointRepository();
            $result = $repos->deleteEntry($args['id']);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write('{ "count" : '.$result.' }');
        });
        
        $this->app->put('/waypoints/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getWaypointRepository();
            $waypointData = json_decode($request->getBody(), TRUE);
            $waypoint = $repos->getEntryById($args['id']);
            $data = $waypoint->getDbSetData();
            foreach ($waypointData as $field => $value) {
                $data[$field] = $value;
            }
            $waypoint->setData($data);
            $waypoint->setId($args['id']);
            $repos->updateEntry($waypoint);
            $result = $repos->getEntityWithAgregatesById($waypoint->getId());
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
    }
    
    private function registerWaypointTypesRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/waypoint_types/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getWaypointTypeRepository();
            $result = $repos->getEntityById($args['id']);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/waypoint_types', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getWaypointTypeRepository();
            $result = $repos->getEntries();
            $json = json_encode(array_values($result));
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
    }
    
    private function registerJobStatusesRoutes(RepositoryProvider $repositoryProvider)
    {
        $this->app->get('/job_statuses/{id}', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getJobStatusRepository();
            $result = $repos->getEntityById($args['id']);
            $json = json_encode($result);
            return $response->withHeader('Content-Type', 'application/json')
                            ->write($json);
        });
        
        $this->app->get('/job_statuses', function (Request $request, Response $response, array $args) use($repositoryProvider) {
            $repos = $repositoryProvider->getJobStatusRepository();
            $result = $repos->getEntries();
            $json = json_encode(array_values($result));
            $response->getBody()->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        });
    }
}
