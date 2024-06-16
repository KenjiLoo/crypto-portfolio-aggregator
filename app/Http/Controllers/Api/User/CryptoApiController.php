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
        $url = 'https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
        'start' => '1',
        'limit' => '5000',
        'convert' => 'USD'
        ];

        $headers = [
        'Accepts: application/json',
        'X-CMC_PRO_API_KEY: b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c'
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
        return json_decode($response); // print json decoded response
        curl_close($curl); // Close request
    }
}
