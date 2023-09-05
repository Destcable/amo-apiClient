<?php

namespace Client\Services;

use Client\Services\BaseService;
use Client\Models\CompanyModel;
use Client\Traits\NoteTrait;

class CompanyService extends BaseService 
{
    use NoteTrait;
    public string $entity = 'companies';
    protected $model = CompanyModel::class;
}