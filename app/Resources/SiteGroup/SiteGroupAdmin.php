<?php

namespace App\Resources\SiteGroup;

use App\Resources\BaseResource;

class SiteGroupAdmin extends BaseResource
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
            'id'                => $this->id,
            "site_ids"          => $this->sites,
            'site_group_id'     => $this->site_group_id,
            'site_profile_id'   => $this->site_profile_id,
            'username'          => $this->username,
            'name'              => $this->name,
            'is_master_account' => $this->is_master_account,
            'status'            => $this->status,
            'meta' => [
                'lastlogin' => $this->last_login_at,
                'created'   => $this->created_at,
                'updated'   => $this->updated_at,
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
