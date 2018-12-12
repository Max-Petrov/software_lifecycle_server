<?php

namespace Kenguru\Logistic\API\DAL\Database;

use PDO;
use PDOStatement;
use PDOException;

use Kenguru\Logistic\API\DAL\Database\Query;
/**
 * Класс-одиночка для непосредственной работы с базой даных
 *
 * @author maxim
 */
class SQLDatabase 
{    
    protected static $instance = null;
    protected $db;

    private function __wakeup() {}
    private function __clone() {}
    
    public static function getInstance()
    {   
        if (is_null(self::$instance)){
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $config = parse_ini_file('db_info/db.ini');
        
        $opt  = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => FALSE,
        );
        
        $dsn = 'mysql:host='.$config['db_host'].';dbname='.$config['db_name'].';charset='.$config['charset'];
        
        try {
            $this->db = new PDO($dsn, $config['db_user'], $config['db_password'], $opt);
        } catch (PDOException $ex) {
            echo 'Проблемы с подключением к базе данных! '. $ex->getMessage();
            die();
        }
        unset($config);
    }
    
    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::getInstance(), $method), $args);
    }

    /** 
     * Метод формирует строку из имен полей и их значений для SET, связывая параметры через оператор "="
     * @param array $allowed - массив с именами полей
     * @return string
     */
    private function pdoSet(array $allowed) : string
    {
        $set = '';
        foreach ($allowed as $field) {
            $set.= $this->escapeString($field)."= ?, ";
        }
        return substr($set, 0, -2);
    }
    
    /**
     * Метод формирует строку для WHERE, позволяет использовать операторы отношений.
     * @param array $allowed - параметры для WHERE в виде массива (имя_поля => оператор_отношения)
     * @return string
     */
    private function pdoWhereSet(array $allowed) : string
    {
        $validOperations = array("=", ">", "<", "<>", ">=", "<=", "LIKE", "like");
        $set = '';
        foreach ($allowed as $field){
            $operation = $field[1];
            if (!in_array($operation, $validOperations)){
                $operation = "=";
            }
            $set.= $this->escapeString($field[0])." ".$operation." ? AND ";
        }
        return substr($set, 0, -5); 
    }
    
    /**
     * Метод формирует строку для ORDER BY
     * @param array $allowed - массив для ORDER BY (имя_поля => порядок_сортировки), порядок сортировки обязателен, либо "ASC", либо "DESC"
     * @return string
     */
    private function pdoOrderBySet(array $allowed) : string 
    {
        $validSort = array("asc", "ASC", "desc", "DESC");
        $order = "";
        foreach ($allowed as $key => $item){
            if (in_array($item, $validSort)){
                $order .= $this->escapeString($key)." ".$item.", ";
            }
        }
        return substr($order, 0, -2);
    }

    /**
     * Метод формирует строку для GROUP BY
     * @param array $fields - массив полей
     * @return string
     */
    private function pdoGroupBySet(array $fields) : string {
        $set = '';
        foreach ($fields as $field) {
            $set.= $this->escapeString($field).", ";
        }
        return substr($set, 0, -2);
    }
    
    /**
     * Метод помещает имя поля или таблицы в обратные кавычки
     * @param type $value - имя поля или таблицы
     * @return string
     */
    private function escapeString($value) : string
    {
        return "`".str_replace("`","``",$value)."`";
    }
    
    /**
     * Метод запускает строковый запрос и возвращает результат
     * @param type $sql - строка SQL-запроса с плейсхолдерами
     * @param type $args - значения для плейсхолдеров (имя_плейсхолдера => значение)
     * @param type $bindParams - строго типизируемые нестроковые значения, например для LIMIT (имя_плейсхолдера или его позиция,
     *  значение, тип)
     * @return PDOStatement - возвращает результат выполнения запроса
     */
    public function run(string $sql, array $args = array(), array $bindParams = array()) : PDOStatement
    {
        $statement = $this->db->prepare($sql);
        foreach ($bindParams as $param){
            $statement->bindValue($param[0], $param[1], $param[2]);
        }
        if (empty($args)){
            $statement->execute();
        }
        else{
            $statement->execute($args);
        }
        return $statement;
    }

    /**
     * Метод обеспечивает вставку новой записи в соответствующую таблицу
     * @param string $tableName - имя таблицы
     * @param array $values - массив (имя_поля => значение)
     */
    public function insert(string $tableName, array $values)
    {
        $fields = array_keys($values);
        $sql = "INSERT INTO ".$this->escapeString($tableName)." SET ".$this->pdoSet($fields);
        
        $statement = $this->db->prepare($sql);
        if (empty($values)){
            $statement = null;
        }
        else{
           $statement->execute(array_values($values)); 
        }
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Метод обеспечивает вставку новой записи в соответствующую таблицу
     * @param string $tableName - имя таблицы
     * @param array $values - массив массивов(имя_поля => значение)
     */
    public function insertEntries(string $tableName, array $values)
    {
        $sql = "INSERT INTO ".$this->escapeString($tableName)." ";
        $fields = array_keys($values[0]);
        $sql .= "(".implode(",", $fields).") VALUES ";
        foreach ($values as $value) {
            $sql .= "('" . implode("','", array_values($value)) . "'), ";
        }
        $sql = substr($sql, 0, -2);
        
        $statement = $this->db->prepare($sql);
        if (empty($values)){
            $statement = null;
        }
        else{
           $statement->execute(); 
        }
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Метод выполняет вставку данных в базу
     * @param string $tableName - имя таблицы
     * @param array $values - массив (имя_поля => значение)
     * @param array $where - массив (имя_поля, оператор_отношения, значение)
     * @return PDOStatement
     */
    public function update(string $tableName, array $values, array $where) : PDOStatement
    {
        $fields = array_keys($values);
        $fieldsWithWhereValues = array();
        $fieldsWithWhereOperations = array();
        foreach ($where as $item){
            array_push($fieldsWithWhereValues, $item[2]);
            array_push($fieldsWithWhereOperations, array($item[0], $item[1]));
        }
        
        $sql = "UPDATE ".$this->escapeString($tableName)." SET ".$this->pdoSet($fields)." WHERE ".$this->pdoWhereSet($fieldsWithWhereOperations);
        $statement = $this->db->prepare($sql);
        $statement->execute(array_merge(array_values($values), $fieldsWithWhereValues));
        
        return $statement;
    }
    
    /**
     * Метод выполняет удаление записи из базы данных
     * @param string $tableName - имя таблицы
     * @param array $where - массив (имя_поля, оператор_отношения, значение)
     * @return PDOStatement
     */
    public function delete(string $tableName, array $where) : PDOStatement
    {
        $fieldsWithWhereValues = array();
        $fieldsWithWhereOperations = array();
        foreach ($where as $item){
            array_push($fieldsWithWhereValues, $item[2]);
            array_push($fieldsWithWhereOperations, array($item[0], $item[1]));
        }
        
        $sql = "DELETE FROM ".$this->escapeString($tableName)." WHERE ".$this->pdoWhereSet($fieldsWithWhereOperations);
        
        $statement = $this->db->prepare($sql);
        $statement->execute($fieldsWithWhereValues);
        
        return $statement;
    }
    
    /**
     * Метод выполняет простой запрос на условную или безусловную выборку из одной таблицы (не поддерживает JOIN и UNION)
     * 
     * @param Query $query - объект класса Query
     * @return PDOStatement
     */
    public function select(Query $query) : PDOStatement
    {
        $tableName = $query->getTableName();
        $fields = $query->getFields();
        $where = $query->getWhere();
        $orderBy = $query->getOrderBy();
        $groupBy = $query->getGroupBy();
        $limit = $query->getLimit();
        $offset = $query->getOffset();
        
        if (!is_numeric($limit)){
            $limit = 100;
        }
        if (!is_numeric($offset)){
            $offset = 0;
        }
        
        $fieldsNames = (empty($fields)) ? '*' : $this->pdoSet($fields);
        
        $whereString = "";
        $fieldsWithWhereValues = array();
        $fieldsWithWhereOperations = array();
        if (!empty($where)){
            foreach ($where as $item){
                array_push($fieldsWithWhereValues, $item[2]);
                array_push($fieldsWithWhereOperations, array($item[0], $item[1]));
            }
            $whereString = " WHERE ".$this->pdoWhereSet($fieldsWithWhereOperations);
        }
        
        $group = (empty($groupBy)) ? "" : " GROUP BY ".$this->pdoGroupBySet($groupBy);
        $order = (empty($orderBy)) ? "" : " ORDER BY ".$this->pdoOrderBySet($orderBy);
        
        $sql = "SELECT ".$fieldsNames." FROM ".$this->escapeString($tableName).$whereString.$group.$order.' LIMIT ?, ?';
        $statement = $this->db->prepare($sql);
        $params = array_merge($fieldsWithWhereValues, array($offset, $limit));
        $statement->execute($params);
        return $statement;
    }
    
    /**
     * Метод добавляет к именам полей обратные кавычки
     * @param array $fields - массив имен полей
     */
    public function escapeFieldsName(array &$fields)
    {
        foreach ($fields as $key => $field){
            $fields[$key] = $this->escapeString($field);
        }
        unset($field);
    } 
}
