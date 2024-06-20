<?php

namespace App\Http\Controllers\Api\User;

use App\Resources\CryptoListing;
use Illuminate\Http\Request;

class CryptoApiController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = '';
        $this->resource = '';
    }

    public function getCryptoInfo (Request $request)
    {
        $url = env('CMC_API_URL');
        $cmcKey = env('CMC_API_KEY');
        $limit = $request->limit;
        $parameters = [
        'start' => '1',
        'limit' => $limit,
        'convert' => 'USD'
        ];

        $headers = [
        'Accepts: application/json',
        "X-CMC_PRO_API_KEY: {$cmcKey}"
        ];

        $qs = http_build_query($parameters); 
        $request = "{$url}?{$qs}"; 
        $curl = curl_init(); 

        // Set cURL options
        curl_setopt_array($curl, array(
        CURLOPT_URL => $request,  
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => 1
        ));

        $response = curl_exec($curl);
        $response = json_decode($response)->data;

        curl_close($curl);

        //format results
        $listing = [];

        foreach ($response as $crypto) {
            $resource = new CryptoListing();
            $listing[] = $resource->getData($crypto); 
        }

        return $listing;
    }

    public function showCryptoList (Request $request) 
    { 
        $listing = $this->getCryptoInfo($request);

        return api()->ok()
            ->data($listing)
            ->flush();
    }
}
