<?php

namespace Tests\Unit;

use App\Core\Container;
use PHPUnit\Framework\TestCase;
use Tests\Services\InvoiceService;
use Tests\Services\StripePayment;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function test_that_it_binds_a_class_with_closure(): void
    {
        $this->container->bind(InvoiceService::class, fn () => "good");

        $expected = "good";

        $this->assertEquals($expected, $this->container->get(InvoiceService::class));

        $this->assertTrue($this->container->has(InvoiceService::class));
    }

    public function test_that_it_binds_a_class_with_other(): void
    {
        $this->container->bind(InvoiceService::class, StripePayment::class);

        $this->assertTrue(
            method_exists($this->container->get(InvoiceService::class), "charge")
        );

        $this->assertTrue($this->container->has(InvoiceService::class));
    }

    public function test_that_it_resolves_all_dependencies_for_the_class(): void
    {
        $this->assertIsBool($this->container->get(InvoiceService::class)->process([], 500));
    }
}
