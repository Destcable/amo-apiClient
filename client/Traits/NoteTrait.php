<?php 

namespace Client\Traits;

use Client\Services\NoteService;

trait NoteTrait
{
    public function createNote()
    {
        return new NoteService($this->config, $this->id, $this->entity);
    }
}