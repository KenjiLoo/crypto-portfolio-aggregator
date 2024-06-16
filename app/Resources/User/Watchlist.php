<?php

namespace App\Resources\User;

use App\Resources\BaseResource;

class Watchlist extends BaseResource
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
            'user_id'       => $this->user_id,
            'crypto_id'     => $this->crypto_id,
            'crypto_name'   => $this->crypto_name,
            'meta' => [
                'created'    => $this->created_at,
                'updated'    => $this->updated_at
            ],
        ];

        return $data;
    }
}
