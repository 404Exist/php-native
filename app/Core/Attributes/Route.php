<?php

namespace App\Core\Attributes;

use App\Core\Enums\HttpMethod;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(public string $path, public HttpMethod $method = HttpMethod::GET)
    {
    }
}
