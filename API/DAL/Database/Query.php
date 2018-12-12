<?php

namespace Kenguru\Logistic\API\DAL\Database;

/**
 * Класс агрегирует данные для SELECT запроса
 *
 * @author maxim
 */
class Query 
{
    /**
     *
     * @var string - имя таблицы  
     */
    private $tableName;
    
    /**
     *
     * @var array - массив имен полей (если нужны все, то пустой массив) 
     */
    private $fields;
    
    /**
     *
     * @var array - массив (имя_поля, оператор_отношения, значение)
     */
    private $where;
    
    /**
     *
     * @var array - массив полей сортировки (имя_поля => порядок). Порядок, либо "ASC", либо "DESC"
     */
    private $orderBy;
    
    /**
     *
     * @var array - массив полей для группировки
     */
    private $groupBy;
    
    /**
     *
     * @var integer - максимальное количество возвращаемых данных
     */
    private $limit;
    
    /**
     *
     * @var integer - смещение от начала
     */
    private $offset;
    
    /**
     * 
     * @param string $tableName - имя таблицы
     * @param type $limit - максимальное количество возвращаемых данных
     * @param type $offset - смещение от начала
     */
    public function __construct(string $tableName, $limit = 100, $offset = 0) 
    {
        $this->tableName = $tableName;
        $this->fields = array();
        $this->where = array(); 
        $this->orderBy = array();
        $this->groupBy = array();
        $this->limit = $limit;
        $this->offset = $offset;
    }

    function getTableName() 
    {
        return $this->tableName;
    }

    function getFields() 
    {
        return $this->fields;
    }

    function getWhere() 
    {
        return $this->where;
    }

    function getOrderBy() 
    {
        return $this->orderBy;
    }

    function getGroupBy() 
    {
        return $this->groupBy;
    }

    function getLimit() 
    {
        return $this->limit;
    }

    function getOffset() 
    {
        return $this->offset;
    }

    function setTableName(string $tableName) 
    {
        $this->tableName = $tableName;
        return self;
    }

    function setFields(array $fields) 
    {
        $this->fields = $fields;
        return self;
    }

    function setWhere(array $where) 
    {
        $this->where = $where;
        return self;
    }

    function setOrderBy(array $orderBy) 
    {
        $this->orderBy = $orderBy;
        return self;
    }

    function setGroupBy(array $groupBy) 
    {
        $this->groupBy = $groupBy;
        return self;
    }

    function setLimit($limit) 
    {
        $this->limit = $limit;
        return self;
    }

    function setOffset($offset) 
    {
        $this->offset = $offset;
        return self;
    }


}
