<?php

namespace App\Resources\SiteGroup;

use App\Resources\BaseResource;

class Site extends BaseResource
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
            'id'            => $this->id,
            'site_group_id' => $this->site_group_id,
            'name'          => $this->name,
            'site_code'     => $this->site_code,
            'company_code'  => $this->company_code,
            'currency'      => $this->currency,
            'usd_rate'      => $this->usd_rate,
            'status'        => $this->status,
            'meta' => [
                'created' => $this->created_at,
                'updated' => $this->updated_at
            ]
        ];

        return $data;
    }
}
