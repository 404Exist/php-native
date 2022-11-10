<?php

namespace Tests\Services;

class StripePayment
{
    public function charge(float $amount, array $customer, float $tax): bool
    {
        return (bool) mt_rand(0, 1);
    }
}
