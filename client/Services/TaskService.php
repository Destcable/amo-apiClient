<?php 

namespace Client\Services;

use Client\Api\Query;
use Client\Services\BaseService;
use Client\Models\TaskModel;

class TaskService extends BaseService
{ 
    public string $entity = 'tasks';
    protected $model = TaskModel::class;

    public function createTask()
    { 
        $this->model = new $this->model();
        return $this;
    }

    public function save()
    {
        $modifiedFields = $this->model->getModifiedFields();
        $requiredFields = $this->model->getRequiredFields();

        if (!$this->compareRequiredArrays($modifiedFields, $requiredFields)) {
            throw new \Exception("Отсутствуют обязательные свойства.");
        }

        $query = new Query($this->config);
        return $query->post('/api/v4/tasks', [$modifiedFields]);
    }
}