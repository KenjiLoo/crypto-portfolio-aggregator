<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class ArrayHelper
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get($key, $default = null)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                $v = Arr::get($this->data, $k);

                if (!empty($v)) {
                    return $v;
                }
            }

            return $default;
        }

        return Arr::get($this->data, $key, $default);
    }
}
