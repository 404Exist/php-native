<?php

namespace App\Examples\Services;

use App\Examples\Interfaces\PaymentGatewayInterface;

class InvoiceService
{
    public function __construct(
        protected SalesTaxService $salesTaxService,
        protected PaymentGatewayInterface $gatewayService,
        protected EmailService $emailService,
    ) {
    }

    public function process(array $customer, float $amount): bool
    {
        $tax = $this->salesTaxService->calculate($amount, $customer);

        if (!$this->gatewayService->charge($amount, $customer, $tax)) {
            dump("Invoice has not been processed");
            return false;
        }

        $this->emailService->send($customer, "receipt");

        dump("Invoice has been processed");

        return true;
    }
}
