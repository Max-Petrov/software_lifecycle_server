<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IRouteJobRepository;
use Kenguru\Logistic\API\DAL\Entities\RouteJob;
use Kenguru\Logistic\API\DAL\Entities\JobStatus;
use Kenguru\Logistic\API\DAL\Repositories\RepositoryProvider;

/**
 * Description of SQLRouteJobRepository
 *
 * @author maxim
 */
class SQLRouteJobRepository extends SQLRepository implements IRouteJobRepository
{
    public function __construct() 
    {
        parent::__construct("route_jobs", RouteJob::class);
    }

    public function getEntitiesWithAgregates($limit = 100, $offset = 0) 
    {
        $sql = "SELECT j.id, j.created_time, j.start_point_latitude, j.start_point_longitude, j.finish_point_address, "
                . "j.finish_point_latitude, j.finish_point_longitude, j.start_time_plan, j.finish_time_plan, j.start_time_fact, "
                . "j.finish_time_fact, j.total_distance, j.driver_registration_id, j.job_status_id, s.name status_name "
                . "FROM route_jobs j "
                . "JOIN job_statuses s ON j.job_status_id = s.id LIMIT ?, ?";
        
        $jobs = array();
        $bindParams = array(
            array(1, $offset, \PDO::PARAM_INT),
            array(2, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $job = new RouteJob();
            $status = new JobStatus();
            $job->setId($entry['id']);
            $job->setCreatedTime($entry['created_time']);
            $job->setStartPointLatitude($entry['start_point_latitude']);
            $job->setStartPointLongitude($entry['start_point_longitude']);
            $job->setFinishPointAddress($entry['finish_point_address']);
            $job->setFinishPointLatitude($entry['finish_point_latitude']);
            $job->setFinishPointLongitude($entry['finish_point_longitude']);
            $job->setStartTimePlan($entry['start_time_plan']);
            $job->setFinishTimePlan($entry['finish_time_plan']);
            $job->setStartTimeFact($entry['start_time_fact']);
            $job->setFinishTimeFact($entry['finish_time_fact']);
            $job->setTotalDistance($entry['total_distance']);
            $job->setDriverRegistrationId($entry['driver_registration_id']);
            $status->setId($entry['job_status_id']);
            $status->setName($entry['status_name']);
            $job->setStatus($status);
            $jobs[$job->getId()] = $job;
            //array_push($jobs, $job);
        }
        return $jobs;
    }

    public function getEntityWithAgregatesById($id) 
    {
        $sql = "SELECT j.id, j.created_time, j.start_point_latitude, j.start_point_longitude, j.finish_point_address, "
                . "j.finish_point_latitude, j.finish_point_longitude, j.start_time_plan, j.finish_time_plan, "
                . "j.start_time_fact, j.finish_time_fact, j.total_distance, j.driver_registration_id, j.job_status_id, "
                . "s.name status_name "
                . "FROM route_jobs j "
                . "JOIN job_statuses s ON j.job_status_id = s.id "
                . "WHERE j.id = ?";
        
        $args = array($id);
        $stmt = $this->db->run($sql, $args);
        $entry = $stmt->fetch();
        
        $job = new RouteJob();
        $status = new JobStatus();
        $job->setId($entry['id']);
        $job->setCreatedTime($entry['created_time']);
        $job->setStartPointLatitude($entry['start_point_latitude']);
        $job->setStartPointLongitude($entry['start_point_longitude']);
        $job->setFinishPointAddress($entry['finish_point_address']);
        $job->setFinishPointLatitude($entry['finish_point_latitude']);
        $job->setFinishPointLongitude($entry['finish_point_longitude']);
        $job->setStartTimePlan($entry['start_time_plan']);
        $job->setFinishTimePlan($entry['finish_time_plan']);
        $job->setStartTimeFact($entry['start_time_fact']);
        $job->setFinishTimeFact($entry['finish_time_fact']);
        $job->setTotalDistance($entry['total_distance']);
        $job->setDriverRegistrationId($entry['driver_registration_id']);
        $status->setId($entry['job_status_id']);
        $status->setName($entry['status_name']);
        $job->setStatus($status);
        
        return $job;
    }

    public function getRouteJobsByDriverRegistrationId($registrationId, $limit = 100, $offset = 0) 
    {
        $sql = "SELECT j.id, j.created_time, j.start_point_latitude, j.start_point_longitude, j.finish_point_address, "
                . "j.finish_point_latitude, j.finish_point_longitude, j.start_time_plan, j.finish_time_plan, j.start_time_fact, "
                . "j.finish_time_fact, j.total_distance, j.driver_registration_id, j.job_status_id, s.name status_name "
                . "FROM route_jobs j "
                . "JOIN job_statuses s ON j.job_status_id = s.id WHERE driver_registration_id = ? LIMIT ?, ?";
        
        $jobs = array();
        $bindParams = array(
            array(1, $registrationId, \PDO::PARAM_INT),
            array(2, $offset, \PDO::PARAM_INT),
            array(3, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $job = new RouteJob();
            $status = new JobStatus();
            $job->setId($entry['id']);
            $job->setCreatedTime($entry['created_time']);
            $job->setStartPointLatitude($entry['start_point_latitude']);
            $job->setStartPointLongitude($entry['start_point_longitude']);
            $job->setFinishPointAddress($entry['finish_point_address']);
            $job->setFinishPointLatitude($entry['finish_point_latitude']);
            $job->setFinishPointLongitude($entry['finish_point_longitude']);
            $job->setStartTimePlan($entry['start_time_plan']);
            $job->setFinishTimePlan($entry['finish_time_plan']);
            $job->setStartTimeFact($entry['start_time_fact']);
            $job->setFinishTimeFact($entry['finish_time_fact']);
            $job->setTotalDistance($entry['total_distance']);
            $job->setDriverRegistrationId($entry['driver_registration_id']);
            $status->setId($entry['job_status_id']);
            $status->setName($entry['status_name']);
            $job->setStatus($status);
            $jobs[$job->getId()] = $job;
            //array_push($jobs, $job);
        }
        return $jobs;
    }

    public function getRouteJobsByDriverRegistrationIdAndStatusId($registrationId, $statusId, $limit = 100, $offset = 0) 
    {
        $sql = "SELECT j.id, j.created_time, j.start_point_latitude, j.start_point_longitude, j.finish_point_address, "
                . "j.finish_point_latitude, j.finish_point_longitude, j.start_time_plan, j.finish_time_plan, j.start_time_fact, "
                . "j.finish_time_fact, j.total_distance, j.driver_registration_id, j.job_status_id, s.name status_name "
                . "FROM route_jobs j "
                . "JOIN job_statuses s ON j.job_status_id = s.id WHERE driver_registration_id = ? AND job_status_id = ? LIMIT ?, ?";
        
        $jobs = array();
        $bindParams = array(
            array(1, $registrationId, \PDO::PARAM_INT),
            array(2, $statusId, \PDO::PARAM_INT),
            array(3, $offset, \PDO::PARAM_INT),
            array(4, $limit, \PDO::PARAM_INT)
            );
        $stmt = $this->db->run($sql, array(), $bindParams);
        
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $job = new RouteJob();
            $status = new JobStatus();
            $job->setId($entry['id']);
            $job->setCreatedTime($entry['created_time']);
            $job->setStartPointLatitude($entry['start_point_latitude']);
            $job->setStartPointLongitude($entry['start_point_longitude']);
            $job->setFinishPointAddress($entry['finish_point_address']);
            $job->setFinishPointLatitude($entry['finish_point_latitude']);
            $job->setFinishPointLongitude($entry['finish_point_longitude']);
            $job->setStartTimePlan($entry['start_time_plan']);
            $job->setFinishTimePlan($entry['finish_time_plan']);
            $job->setStartTimeFact($entry['start_time_fact']);
            $job->setFinishTimeFact($entry['finish_time_fact']);
            $job->setTotalDistance($entry['total_distance']);
            $job->setDriverRegistrationId($entry['driver_registration_id']);
            $status->setId($entry['job_status_id']);
            $status->setName($entry['status_name']);
            $job->setStatus($status);
            $jobs[$job->getId()] = $job;
            //array_push($jobs, $job);
        }
        return $jobs;
    }

    public function getRouteJobsByConditions($registrationId = null, $periodStart = null, $periodFinish = null, $statusId = null, $limit = 100, $offset = 0) 
    {
        $where = array();
        if (is_numeric($registrationId)){
            array_push($where, array('driver_registration_id', '=', $registrationId));
        }
        if (is_string($periodStart)){
            array_push($where, array('created_time', '>=', $periodStart));
        }
        if (is_string($periodFinish)){
            array_push($where, array('created_time', '<=', $periodFinish));
        }
        if (is_numeric($statusId)){
            array_push($where, array('job_status_id', '=', $statusId));
        }
        
        $routes = $this->getEntriesWithWhere($where, null, $limit, $offset);
        
        $provider = new RepositoryProvider();
        $statusRepos = $provider->getJobStatusRepository();
        $statuses = $statusRepos->getEntries();
        
        foreach (array_values($routes) as $route){
            $route->setStatus($statuses[$route->getStatusId()]);
        }
        
        return $routes;
    }
    
    private function getCurrentRouteJobByStatusAndRegistrationId($registrationId, $statusId) {
        $where = array();
        array_push($where, array('driver_registration_id', '=', $registrationId));
        array_push($where, array('job_status_id', '=', $statusId));
        
        $orderBy = [
            'created_time' => 'ASC'
        ];
        
        $route = array_values($this->getEntriesWithWhere($where, $orderBy, 1))[0];
        
        return $route;
    }
    
    public function getCurrentRouteJobByRegistrationId($registrationId) {
        $route = $this->getCurrentRouteJobByStatusAndRegistrationId($registrationId, 3);
        if (is_null($route)) {
            $route = $this->getCurrentRouteJobByStatusAndRegistrationId($registrationId, 2);
        }
        if (!is_null($route)) {
            $provider = new RepositoryProvider();
            $statusRepos = $provider->getJobStatusRepository();
            $statuses = $statusRepos->getEntries();

            if (!is_null($route)) {
                $route->setStatus($statuses[$route->getStatusId()]);
            }
            $route->setStatus($statuses[$route->getStatusId()]);
        }
        return $route;
    }
}
