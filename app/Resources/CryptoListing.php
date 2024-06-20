<?php

namespace App\Resources;

use App\Resources\BaseResource;

class CryptoListing
{
    /**
     * Transform the resource into an array.
     *
     * @param  object provided 
     * @return array
     */
    public static function getData($object)
    {
        $data = [
            'id'       => $object->id,
            'slug'     => $object->slug,
            'name'     => $object->name,
            'symbol'   => $object->symbol,
            'price'    => $object->quote->USD->price
        ];

        return $data;
    }
}
