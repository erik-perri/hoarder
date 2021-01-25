<?php

namespace App\Http\GuzzleHttp;

use GuzzleHttp\Psr7\Request;
use Http\Message\RequestFactory;

class GuzzleRequestFactory implements RequestFactory
{
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1'): Request
    {
        return new Request($method, $uri, $headers, $body, $protocolVersion);
    }
}
