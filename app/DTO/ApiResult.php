<?php

namespace App\DTO;

class ApiResult
{
    public function __construct(
        public readonly string $ip,
        public readonly string $countryCode
    ) {
    }
}
