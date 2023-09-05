<?php

namespace Client\Services;

use Client\Services\BaseService;
use Client\Models\LeadModel;

class LeadService extends BaseService
{ 
    public string $entity = 'leads';
    protected $model = LeadModel::class;
}