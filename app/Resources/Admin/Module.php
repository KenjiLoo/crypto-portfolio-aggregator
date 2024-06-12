<?php

namespace App\Resources\Admin;

use App\Resources\BaseResource;

class Module extends BaseResource
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
            'master_id' => $this->master_id,
            'master_module_filter' => strtoupper($this->type) . ' · ' . $this->name,
            'type'      => $this->type,
            'modulekey' => $this->modulekey,
            'name'      => $this->name,
            'sequence'  => $this->sequence,
            'icon'      => $this->icon,
            'route'     => $this->route,
            'is_superadmin' => $this->is_superadmin,
            'is_master' => $this->is_master,
            'is_hidden' => $this->is_hidden,
            'meta' => [
                'created' => $this->created_at,
                'updated' => $this->updated_at
            ],
        ];

        return $data;
    }
}
