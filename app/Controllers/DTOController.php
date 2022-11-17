<?php

namespace App\Controllers;

use App\Core\Attributes\Get;
use App\DTO\ApiResult;

class DTOController
{
    #[Get("/DTO")]
    public function index()
    {
        dd(
            $this->getFirstApiResult(),
            $this->getSecondApiResult()
        );
    }

    public function getFirstApiResult(): ApiResult
    {
        $result = $this->apiResult("https://ipinfo.io/json");

        return new ApiResult($result->ip, $result->country);
    }

    public function getSecondApiResult(): ApiResult
    {
        $result2 = $this->apiResult("http://ip-api.com/json");

        return new ApiResult($result2->query, $result2->countryCode);
    }

    public function apiResult(string $url): object
    {
        $handle = curl_init();

        curl_setopt_array($handle, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        return json_decode(curl_exec($handle));
    }
}
