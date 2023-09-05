<?php

namespace Client\Models;

abstract class BaseModel
{ 
    protected array $modifiedFields = [];
    protected array $required = [];
    protected array $writable = [];

    public function __set($key, $value)
    {
        if (in_array($key, $this->writable) || in_array($key, $this->required)) {
            $this->modifiedFields [$key]= $value;
        } else { 
            throw new \Exception("Свойство '{$key}' не разрешено.");
        }   
    }

    public function __get($key)
    {
        return $this->modifiedFields[$key] ?? $this->data[$key] ?? null;
    }
    
    public function getModifiedFields(): array
    { 
        return $this->modifiedFields;
    }

    public function getRequiredFields(): array
    { 
        return $this->required;
    }

}