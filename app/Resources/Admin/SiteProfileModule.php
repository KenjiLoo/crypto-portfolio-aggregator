<?php

namespace App\Resources\Admin;

use App\Resources\BaseResource;

class SiteProfileModule extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return array
     */
    public function getData($request)
    {
        $data = [
            'id' => $this->id,
            'site_profile_id' => $this->site_profile_id,
            'module_id' => $this->module_id,
        ];

        return $data;
    }
}
