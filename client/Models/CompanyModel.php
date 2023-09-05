<?php

namespace Client\Models;

use Client\Models\BaseModel;

class CompanyModel extends BaseModel
{ 
    protected array $writable = [ 
        'first_name'
    ];

    protected array $required = [ 
        'name'
    ];
}