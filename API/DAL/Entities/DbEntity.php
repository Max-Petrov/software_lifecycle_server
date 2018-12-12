<?php

namespace Kenguru\Logistic\API\DAL\Entities;

/**
 * Класс сущности из БД.
 * Реализует агрегацию данных, т.е.
 * позволяет привязывать к этой сущности
 * другие сущности или массивы
 *
 * @author maxim
 */

abstract class DbEntity implements \JsonSerializable
{
    // каждый сущность из базы данных имеет id
    protected $id;
    // хранит связанные данные (предназначено для хранения данных по связи "один ко многим")
    protected $includedData;
    // режим сериализации, определяет сериализовать ли связанные данные
    private $serializeIncluded;

    public function __construct() {
        $this->id = 0;
        $this->serializeIncluded = TRUE;
        $this->includedData = array();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    // присоединяет данные
    public function includeData($agregat, $name)
    {
        $this->includedData[$name] = $agregat;
    }

    // сериализует все данные до двух уровней вложенности
    public function jsonSerialize() 
    {
        if (isset($this->includedData) && $this->serializeIncluded === TRUE){
            return array_merge($this->getData(), $this->includedData);
        }
        return $this->getData();
    }

    // устанавливает режим сериализации
    public function setSerializeIncludedData($value = TRUE)
    {
        $this->serializeIncluded = $value;
    }
    
    // обертка для json_encode
    public function jsonEncode($full = TRUE)
    {
        $this->setSerializeIncludedData($full);
        return json_encode($this);
    }
    
    abstract public function setData($values);

    // устанавливает сериализуемые поля
    abstract public function getData();
    
    // выдает поля для вставки в бд
    abstract public function getDbSetData();
}
