<?php

namespace App\Resources\Admin;

use App\Resources\BaseResource;

class AdminProfile extends BaseResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'is_superadmin' => $this->is_superadmin,
            'meta' => [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
            ],
        ];

        return $data;
    }
}
