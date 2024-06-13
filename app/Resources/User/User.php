<?php

namespace App\Resources\User;

use App\Resources\BaseResource;

class User extends BaseResource
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
            'username'      => $this->username,
            'name'          => $this->name,
            'meta' => [
                'created'    => $this->created_at,
                'updated'    => $this->updated_at,
                'last_login' => $this->last_login_at,
            ],
        ];

        return $data;
    }
}
