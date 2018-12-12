<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IDriverRegistrationRepository;
use Kenguru\Logistic\API\DAL\Entities\DriverRegistration;
use Kenguru\Logistic\API\DAL\Database\Query;

/**
 * Description of SQLDriverRegistrationRepository
 *
 * @author maxim
 */
class SQLDriverRegistrationRepository extends SQLRepository implements IDriverRegistrationRepository
{
    public function __construct() 
    {
        parent::__construct("driver_registrations", DriverRegistration::class);
    }

    public function getDriverRegistrationsByConditions($actual = null, $periodStart = null, $periodFinish = null, $driverId = null,
            $deviceId = null, $vehicleId = null, $limit = 100, $offset = 0) 
    {
        $where = array();
        if (is_numeric($actual)){
            if ($actual == true){
                array_push($where, array('actual', '=', 1));
            }
            else{
                array_push($where, array('actual', '=', 0));
            }
        }
        if (is_string($periodStart)){
            array_push($where, array('time_start', '>=', $periodStart));
        }
        if (is_string($periodFinish)){
            array_push($where, array('time_start', '<=', $periodFinish));
        }
        if (is_numeric($driverId)){
            array_push($where, array('driver_id', '=', $driverId));
        }
        if (is_numeric($deviceId)){
            array_push($where, array('device_id', '=', $deviceId));
        }
        if (is_numeric($vehicleId)){
            array_push($where, array('vehicle_id', '=', $vehicleId));
        }
        
        $orderBy = [
            'actual' => 'DESC',
            'time_start' => 'DESC'
        ];
        
        $registrations = $this->getEntriesWithWhere($where, $orderBy, $limit, $offset);
        return $registrations;
    }

}
