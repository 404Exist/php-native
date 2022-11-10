<?php

namespace App\Examples\Services;

use App\Examples\Interfaces\PaymentGatewayInterface;

class StripePayment implements PaymentGatewayInterface
{
    public function charge(float $amount, array $customer, float $tax): bool
    {
        return (bool) mt_rand(0, 1);
    }
}
