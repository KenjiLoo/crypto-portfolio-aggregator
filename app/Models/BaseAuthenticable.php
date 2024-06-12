<?php

namespace App\Models;

use App\Models\Traits\{Includable, Filterable, Savable, Validatable, Sortable};
use Illuminate\Foundation\Auth\User as Authenticable;

class BaseAuthenticable extends Authenticable
{
    use Includable, Filterable, Savable, Validatable, Sortable;

    public $timzoneFields = [];

    /**
     *
     */
    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function parseFormValue($attribute)
    {
        return $this->$attribute;
    }

    public function isTimezoneField($field)
    {
        return in_array($field, $this->timzoneFields);
    }
}
