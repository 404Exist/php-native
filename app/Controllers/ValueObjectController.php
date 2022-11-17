<?php

namespace App\Controllers;

use App\Core\Attributes\Get;
use App\VO\BillableWeightCalculator;
use App\VO\DimDivisor;
use App\VO\PackageDimension;
use App\VO\Weight;

class ValueObjectController
{
    #[Get("/VO")]
    public function index()
    {
        dd(
            BillableWeightCalculator::calculate(
                new PackageDimension(15, 25, 12),
                new Weight(7),
                DimDivisor::FEDEX,
            )
        );
    }
}
