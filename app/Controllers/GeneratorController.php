<?php

namespace App\Controllers;

use App\Core\Attributes\Get;

class GeneratorController
{
    #[Get("/generators")]
    public function index()
    {
        $numbers = lazyRange(1, 10);
        // $numbers = lazyRange(1, 3000000);

        foreach ($numbers as $number) {
            dump($number);
        }
    }
}
