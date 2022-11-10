<?php

namespace App\Examples\Interfaces;

interface PaymentGatewayInterface
{
    public function charge(float $amount, array $customer, float $tax): bool;
}
