<?php

namespace Client\Services;

use Client\Api\Query;

class BaseService
{
    public int $id;
    public string $entity;
    public array $config;
    protected $model;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __set($name, $value)
    {
        return $this->model->$name = $value;   
    }

    public function __get($name)
    {
        return $this->model->$name;
    }

    public function getById(int $id)
    {
        $this->id = $id;
        $query = new Query($this->config);
        $http = '/api/v4/' . $this->entity . '/' . $this->id;
        $response = $query->get($http);

        if ($response['status'] == 200) {
            $this->model = new $this->model();
            return $this;
        }

        return $response['status'];
    }

    public function create()
    { 
        $this->model = new $this->model();
        return $this;
    }

    public function save()
    {
        $modifiedFields = $this->model->getModifiedFields();
        $requiredFields = $this->model->getRequiredFields();

        if (!isset($this->id) && !$this->compareRequiredArrays($modifiedFields, $requiredFields)) {
            throw new \Exception("Отсутствуют обязательные свойства.");
        }

        $query = new Query($this->config);

        if (isset($this->id)) {
            return $query->patch($this->createRequestURL($this->entity, $this->id), $modifiedFields);
        }

        return $query->post($this->createRequestURL($this->entity), [$modifiedFields]);
    }

    public function createRequestURL(string $path, int $id = null )
    { 
        $defPoint = '/api/v4/';

        if (isset($id)) { 
            return $defPoint . $path . '/' . $id;
        }

        return $defPoint . $path;
    } 


    protected function compareRequiredArrays(array $data, array $required): bool
    {
        if (empty($required)) {
            return true;
        }

        $data = array_keys($data);
        $counter = 0;
        
        foreach ($data as $key) {
            if (in_array($key, $required)) {
                $counter++;
            }
        };
        
        return $counter === count($required);
    } 
}
