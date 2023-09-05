<?php

namespace Client\Models;

use Client\Models\BaseModel;

class TaskModel extends BaseModel
{ 
    protected array $writable = [ 
        'entity_type',
        'entity_id'
    ];

    protected array $required = [
        'complete_till',
        'text'
    ];
}