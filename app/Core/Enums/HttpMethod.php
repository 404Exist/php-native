<?php

namespace App\Core\Enums;

enum HttpMethod: string
{
    case GET = "get";
    case POST = "post";
    case PUT = "put";
    case HEAD = "head";
}
