<?php

namespace App\VO;

use InvalidArgumentException;

class PackageDimension
{
    public function __construct(
        public readonly int $width,
        public readonly int $height,
        public readonly int $length,
    ) {
        if ($width <= 0 || $height <= 0 || $length <= 0) {
            throw new InvalidArgumentException("Package dimensions are invalid");
        }
    }
}
