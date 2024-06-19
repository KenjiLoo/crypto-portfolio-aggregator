<?php

namespace App\Resources\User;

use App\Resources\BaseResource;

class Portfolio extends BaseResource
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
            'unit'          => $this->unit,
            'buy_price'     => $this->buy_price,
            'meta' => [
                'created'    => $this->created_at,
                'updated'    => $this->updated_at
            ],
        ];

        return $data;
    }
}
