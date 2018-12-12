<?php

namespace Kenguru\Logistic\API\DAL\Repositories\Interfaces;

/**
 *
 * Интерфейс рекомендуется реализовывать для сущностей, которые содержат справочные данные.
 * @author maxim
 */
interface IAgregateEntityRepository
{
    /**
     * Метод для выбора сущности по id с загрузкой данных из справочников
     * @param type $id
     */
    public function getEntityWithAgregatesById($id);
    
    /**
     * Метод для выбора сущностей с загрузкой справочных данных
     * @param type $limit
     * @param type $offset
     */
    public function getEntitiesWithAgregates($limit = 100, $offset = 0);
}
