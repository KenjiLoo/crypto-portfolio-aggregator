<?php

namespace App\Models\Traits;

trait Filterable
{
    public $specialFilterFields = [];
    public $filterable = [];
    public $equalable = [];
    
    public function getQuery()
    {
        return $this->query();
    }

    public function isFilterable($field)
    {
        return in_array($field, $this->filterable);
    }

    public function isEqualable($field)
    {
        return in_array($field, $this->equalable);
    }

    public function isSpecialFilterField($field)
    {
        return in_array($field, $this->specialFilterFields);
    }

    public function apply($builder, $custom = [])
    {
        
    }

    public function filterByField($query, $field, $filter)
    {
        //custom filters
    }
}
