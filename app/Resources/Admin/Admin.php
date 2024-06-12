<?php

namespace App\Resources\Admin;

use App\Resources\BaseResource;

class Admin extends BaseResource
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
            'admin_profile_id' => $this->admin_profile_id,
            'username'      => $this->username,
            'name'          => $this->name,
            'is_superadmin' => $this->is_superadmin,
            'status'        => $this->status,
            'meta' => [
                'created'    => $this->created_at,
                'updated'    => $this->updated_at,
                'last_login' => $this->last_login_at,
            ],
        ];

        $includes = isset($request->includes) ?
            array_map('trim', explode(',', $request->includes)) : [];

        if (in_array('allowed_modules', $includes)) {
            $data['allowed_modules'] = $this->resource->getAllowedModuleKeys();
        }

        return $data;
    }
}
