<?php

namespace App\Resources\Admin;

use App\Resources\BaseResource;

class AuditLog extends BaseResource
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
            'module'    => $this->module,
            'action'    => $this->action,
            'url'       => $this->url,
            'status'    => $this->status,
            'ip'        => $this->ip,
            'user_type' => $this->user_type,
            'user_id'   => $this->user_id,
            'username'  => $this->username,
            'info' => [
                'request'  => $this->request,
                'response' => $this->response,
            ],
            'meta' => [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
            ],
        ];

        return $data;
    }
}
