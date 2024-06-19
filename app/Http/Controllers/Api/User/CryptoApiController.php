<?php

namespace App\Http\Controllers\Api\User;

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
        $parameters = [
        'start' => '1',
        'limit' => '5000',
        'convert' => 'USD'
        ];

        $headers = [
        'Accepts: application/json',
        "X-CMC_PRO_API_KEY: {$cmcKey}"
        ];

        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL
        $curl = curl_init(); // Get cURL resource

        // Set cURL options
        curl_setopt_array($curl, array(
        CURLOPT_URL => $request,            // set the request URL
        CURLOPT_HTTPHEADER => $headers,     // set the headers
        CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response

        return api()->ok()
            ->data(json_decode($response))
            ->flush();

        curl_close($curl); // Close request
    }
}
