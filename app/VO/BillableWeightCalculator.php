<?php

namespace App\VO;

class BillableWeightCalculator
{
    public static function calculate(
        PackageDimension $dimension,
        Weight $weight,
        DimDivisor $dimDivisor,
    ): int {
        $dimWeight = (int) round($dimension->width * $dimension->height * $dimension->length / $dimDivisor->value);

        return max($weight->value, $dimWeight);
    }
}
