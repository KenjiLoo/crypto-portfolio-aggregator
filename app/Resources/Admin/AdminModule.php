<?php

namespace App\Resources\Admin;

use App\Resources\BaseResource;

class AdminModule extends BaseResource
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
            'id'        => $this->id,
            'admin_id'  => $this->admin_id,
            'module_id' => $this->module_id,
            'status'    => $this->status,
            'meta' => [
                'created' => $this->created_at,
                'updated' => $this->updated_at
            ],
        ];

        return $data;
    }
}
