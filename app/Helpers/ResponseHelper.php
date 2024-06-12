<?php

namespace App\Helpers;

class ResponseHelper
{
    private $response = [
        'status' => true,
        'message' => 'OK',
    ];

    private $headers = [];
    private $httpStatus = 200;


    public function __construct()
    {
        $this->response = [
            'status'  => true,
            'message' => 'OK',
        ];
    }

    public function ok()
    {
        $this->httpStatus = 200;
        $this->response['status'] = true;

        return $this;
    }

    public function created()
    {
        $this->httpStatus = 201;
        $this->response['status'] = true;

        return $this;
    }

    public function limited()
    {
        $this->httpStatus = 429;
        $this->response['status'] = false;

        return $this;
    }

    public function badRequest()
    {
        $this->httpStatus = 400;
        $this->response['status'] = false;

        return $this;
    }

    public function unauthenticated()
    {
        $this->httpStatus = 401;
        $this->response['status'] = false;

        return $this;
    }

    public function notFound()
    {
        $this->httpStatus = 404;
        $this->response['status'] = false;

        return $this;
    }

    public function formError()
    {
        $this->httpStatus = 422;
        $this->response['status'] = false;

        return $this;
    }

    public function noContent()
    {
        $this->httpStatus = 204;
        $this->response['status'] = true;

        return $this;
    }

    public function forbidden()
    {
        $this->httpStatus = 403;
        $this->response['status'] = false;

        return $this;
    }

    public function exception($status = 500)
    {
        $this->httpStatus = $status;
        $this->response['status'] = false;

        return $this;
    }

    public function message($msg = 'OK')
    {
        $this->response['message'] = $msg;
        return $this;
    }

    public function errors($errors = [])
    {
        $this->response['errors'] = $errors;
        return $this;
    }

    public function data($data = [])
    {
        // if (is_null($data)) {
        //     return $this;
        // }

        $this->response['data'] = $data;

        return $this;
    }

    public function meta($meta = [])
    {
        if (is_null($meta)) {
            return $this;
        }

        $this->response['meta'] = $meta;

        return $this;
    }

    public function remark($remark)
    {
        $this->response['remark'] = $remark;
        return $this;
    }

    public function setFormErrors($messageBag)
    {
        $errors = [];

        foreach ($messageBag as $key => $messages) {
            $errors[$key] = [];
            foreach ($messages as $msg) {
                //$errors[$key][] = str_replace([':message', ':key'], [$msg, $key], ':message');
                $errors[$key][] = str_replace([':message', ':key'], [$msg, $key], ':message');
            }
        }

        return $this->errors($errors);
    }

    public function addHeaders($headers)
    {
        foreach ($headers as $k => $v) {
            $this->headers[$k] = $v;
        }

        return $this;
    }

    public function addField($key, $value, $ignoreNull = true)
    {
        if ($ignoreNull && is_null($value)) {
            return $this;
        }

        $this->response[$key] = $value;

        return $this;
    }

    public function flush()
    {
        if ($this->httpStatus == 204) {
            return response()->noContent();
        }

        $this->response['http_status'] = $this->httpStatus;

        return response()->json($this->response, $this->httpStatus, $this->headers);
    }
}
