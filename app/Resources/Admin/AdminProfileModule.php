<?php

namespace App\Resources\Admin;

use App\Resources\BaseResource;

class AdminProfileModule extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getData($request)
    {
        $data = [
            'id'               => $this->id,
            'admin_profile_id' => $this->admin_profile_id,
            'module_id'        => $this->module_id,
            'meta' => [
                'created' => $this->created_at,
                'updated' => $this->updated_at
            ],
        ];

        return $data;
    }
}
