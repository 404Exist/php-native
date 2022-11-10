<?php

namespace Tests\Services;

class InvoiceService
{
    public function __construct(
        protected SalesTaxService $salesTaxService,
        protected StripePayment $gatewayService,
        protected EmailService $emailService,
    ) {
    }

    public function process(array $customer, float $amount): bool
    {
        $tax = $this->salesTaxService->calculate($amount, $customer);

        if (!$this->gatewayService->charge($amount, $customer, $tax)) {
            return false;
        }

        $this->emailService->send($customer, "receipt");

        return true;
    }
}
