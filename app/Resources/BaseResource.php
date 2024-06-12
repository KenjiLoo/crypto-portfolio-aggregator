<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    public static $isbyInclude = false;

    abstract function getData($request);

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return array
     */
    public function toArray($request)
    {
        $includes = isset($request->includes) ? explode(',', $request->includes) : [];
        $includes = $this->filterIncludes($includes);
        $includable = $this->resource->getIncludable();

        $data = $this->getData($request);

        foreach ($includes as $inc) {
            list($type, $res) = $includable[$inc];

            if (isset($data[$inc])) {
                continue;
            }

            if ($type == 'collection') {
                $res::$isbyInclude = true;
                $data[$inc] = $this->$inc ? $res::collection($this->$inc) : [];
            } else if ($type == 'single') {
                $res::$isbyInclude = true;
                $data[$inc] = $res::make($this->$inc);
            } else if ($type == 'raw') {
                $data[$inc] = $this->$inc;
            }
        }

        return $data;
    }
}