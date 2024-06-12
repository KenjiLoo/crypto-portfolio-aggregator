<?php

namespace App\Models;

use App\Models\Traits\{Includable, Filterable, Savable, Validatable, Sortable};
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
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
