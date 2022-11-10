<?php

namespace App\Exceptions;

class RouteMethodNotAllowedException extends \Exception
{
    public static function create(array $allowedMethods, string $method)
    {
        http_response_code(405);

        $verb = count($allowedMethods) > 1 ? "methods are" : "method is";

        $allowedMethods = strtoupper(implode(", ", $allowedMethods));

        $message = strtoupper($method) . " method isn't allowed for this route, allowed $verb $allowedMethods";

        return new static($message);
    }
}
