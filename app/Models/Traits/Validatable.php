<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Validator;

trait Validatable
{
    protected $rules = [];
    protected $customMessages = [];
    protected $validationAttributes = [];
    protected $ignoreId;
    public $errors = [];

    public function validate($data, $id = null, $intersectOnly = false, $custom = null, $fields = null)
    {
        $idOnly = $id == null ? 'NULL' : $id;
        $this->ignoreId = $idOnly;
        $rules = !empty($custom) ? $custom : $this->rules;

        $filtered = [];
        foreach ($rules as $field => $rule) {
            if (is_array($fields) && !in_array($field, $fields)) {
                continue;
            }

            if (is_string($rule)) {
                $filtered[$field] = str_replace('[id]', $idOnly, $rule);
            } else {
                $filtered[$field] = $rule;
            }
        }

        if ($intersectOnly) {
            $filteredRules = [];
            $dataKeys = array_keys($data);

            foreach ($filtered as $k => $v) {
                $firstKey = explode('.', $k)[0];

                if (in_array($firstKey, $dataKeys)) {
                    $filteredRules[$k] = $v;
                }
            }

            $filtered = $filteredRules;
        }

        $v = Validator::make($data, $filtered, $this->customMessages);
        $v->setAttributeNames($this->validationAttributes);

        if ($v->fails()) {
            $this->errors = $v->errors()->getMessages();
            return false;
        }

        return true;
    }
}
