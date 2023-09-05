<?php

namespace Client\Models;

use Client\Models\BaseModel;

class LeadModel extends BaseModel
{ 
    protected array $writable = [ 
        'status_id'
    ];

    protected array $required = [ 
        'name'
    ];
}