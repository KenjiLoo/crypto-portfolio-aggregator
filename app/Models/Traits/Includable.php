<?php

namespace App\Models\Traits;

trait Includable
{
    protected $includable = [];

    public function getIncludable()
    {
        return $this->includable;
    }

    public function filterIncludes($includes)
    {
        $parsed = [];

        foreach ($includes as $inc) {
            $inc = trim($inc);
            if (isset($this->includable[$inc])) {
                $parsed[] = $inc;
            }
        }

        return $parsed;
    }
}
