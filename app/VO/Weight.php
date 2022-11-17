<?php

namespace App\VO;

use InvalidArgumentException;

class Weight
{
    public function __construct(
        public readonly int $value,
    ) {
        if ($value <= 0) {
            throw new InvalidArgumentException("Weight value is invalid");
        }
    }
}
