<?php

namespace Client\Services;

use Client\Api\Query;
use Client\Services\BaseService;
use Client\Models\NoteModel;

class NoteService extends BaseService
{ 
    public array $config;
    public int $id;
    public string $entity;
    protected $model = NoteModel::class;

    public function __construct(array $config, int $id, string $entity)
    {
        $this->config = $config;
        $this->id = $id;
        $this->entity = $entity;
        $this->model = new NoteModel();
    }

    public function save()
    {
        $query = new Query($this->config);
        $modifiedFields = $this->model->getModifiedFields();
        $requiredFields = $this->model->getRequiredFields();

        if (!$this->compareRequiredArrays($modifiedFields, $requiredFields)) {
            throw new \Exception("Отсутствуют обязательные свойства.");
        }

        if (!isset($this->modifiedFields['note_type'])) {
            $modifiedFields ['note_type']= 'common';
        }

        return $query->post('/api/v4/' . $this->entity . '/' . $this->id . '/notes', [$modifiedFields]);
    }
} 