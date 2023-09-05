<?php

namespace Client\Models;

use Client\Models\BaseModel;

class NoteModel extends BaseModel
{
    protected array $writable = [ 
        'note_type'
    ];

    protected array $required = [
        'text'
    ];
}