<?php

namespace Kenguru\Logistic\API\DAL\Repositories\SQLImplementations;

use Kenguru\Logistic\API\DAL\Repositories\Interfaces\IRepository;
use Kenguru\Logistic\API\DAL\Entities\DbEntity;
use Kenguru\Logistic\API\DAL\Database\SQLDatabase;
use Kenguru\Logistic\API\DAL\Database\Query;

use ReflectionClass;

/**
 * Description of SQLRepository
 *
 * @author maxim
 */
class SQLRepository implements IRepository
{
    protected $db;
    public $defaultPageSize = 100;
    
    protected $tableName;
    private $className;
            
    public function __construct(string $tableName, string $className) 
    {
        $this->db = SQLDatabase::getInstance();
        $this->tableName = $tableName;
        $this->className = $className;
    }

    public function deleteEntry($id) 
    {
        $stmt = $this->db->delete($this->tableName, array(array("id", "=", $id)));
        return $stmt->rowCount();
    }

    public function getEntries($limit = 100, $offset = 0)
    {
        $query = new Query($this->tableName, $limit, $offset);
        $stmt = $this->db->select($query);
        $entities = array();
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $class = new ReflectionClass($this->className);
            $entity = $class->newInstance();
            $entity->setData($entry);
            $entities[$entity->getId()] = $entity;
            //array_push($entities, $entity);
        }
        return $entities;
    }

    public function getEntryById($id) 
    {
        $query = new Query($this->tableName, 1, 0);
        $query->setWhere(array(array("id", "=", $id)));
        $stmt = $this->db->select($query);
        $entry = $stmt->fetch();
        
        $class = new ReflectionClass($this->className);
        $entity = $class->newInstance();
        $entity->setData($entry);
        
        return $entity;
    }

    public function insertEntry(DbEntity $entity) 
    {
        $args = $entity->getDbSetData();
        unset($args['id']);
        $id = $this->db->insert($this->tableName, $args);
        return ['id' => $id];
    }
    
    public function insertEntries(array $entities)
    {
        $queryData = array();
        
        foreach ($entities as $entity) {
            $args = $entity->getDbSetData();
            unset($args['id']);
            array_push($queryData, $args);
        }
        $id = $this->db->insertEntries($this->tableName, $queryData);
        return ['id' => $id];
    }

    public function updateEntry(DbEntity $entity)
    {
        $args = $entity->getDbSetData();
        unset($args['id']);
        $stmt = $this->db->update($this->tableName, $args, array(array("id", "=", $entity->getId())));
        return $stmt->rowCount();
    }
    
    protected function getEntriesWithWhere($where, $orderBy = null, $limit = 100, $offset = 0)
    {
        $query = new Query($this->tableName, $limit, $offset);
        if (is_array($where) && !empty($where)){
            $query->setWhere ($where);
        }
        if (!is_null($orderBy)){
            $query->setOrderBy($orderBy);
        }
        $stmt = $this->db->select($query);
        $entities = array();
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $class = new ReflectionClass($this->className);
            $entity = $class->newInstance();
            $entity->setData($entry);
            $entities[$entity->getId()] = $entity;
        }
        return $entities;
    }
    
    protected function getEntryWithWhere($where)
    {
        $query = new Query($this->tableName, 1, 0);
        if (is_array($where) && !empty($where)){
            $query->setWhere ($where);
        }
        $stmt = $this->db->select($query);
        $entry = $stmt->fetch();
        $class = new ReflectionClass($this->className);
        $entity = $class->newInstance();
        $entity->setData($entry);
        return $entity;
    }
    
    protected function getEntriesByCustomQuery($sql, $args = array(), $bindParams = array())
    {
        
        $stmt = $this->db->run($sql, $args, $bindParams);
        
        $entities = array();
        $entries = $stmt->fetchAll();
        foreach ($entries as $entry){
            $class = new ReflectionClass($this->className);
            $entity = $class->newInstance();
            $entity->setData($entry);
            $entities[$entity->getId()] = $entity;
        }
        return $entities;
    }
    
    protected function getEntryByCustomQuery($sql, $args = array(), $bindParams = array())
    {
        $stmt = $this->db->run($sql, $args, $bindParams);
        $entry = $stmt->fetch();
        $class = new ReflectionClass($this->className);
        $entity = $class->newInstance();
        $entity->setData($entry);
        return $entity;
    }
}
