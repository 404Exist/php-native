<?php

namespace App\Core;

use App\Examples\Interfaces\PaymentGatewayInterface;
use App\Examples\Services\StripePayment;
use App\Exceptions\RouteNotFoundException;
use App\Exceptions\ViewNotFoundException;
use Dotenv\Dotenv;
use Symfony\Component\Mailer\MailerInterface;

class App
{
    private static DB $db;

    public function __construct(
        protected Container $container,
        protected Router $router,
    ) {
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function boot(): static
    {
        session_start();

        $dotenv = Dotenv::createImmutable(dirname(__DIR__ . "/../../../"));
        $dotenv->load();

        static::$db = DB::instance();

        $this->container->bind(PaymentGatewayInterface::class, StripePayment::class);
        $this->container->bind(MailerInterface::class, Mail::class);

        return $this;
    }

    public function run()
    {
        try {
            $route = explode("?", $_SERVER["REQUEST_URI"])[0];

            echo $this->router->resolve($route, $_SERVER['REQUEST_METHOD']);
        } catch (RouteNotFoundException | ViewNotFoundException $e) {
            http_response_code(404);
            dd($e->getMessage() . "<br /><br />" . $e->getFile() . ":" . $e->getLine());
        } catch (\Throwable $e) {
            dd($e->getMessage() . "<br /><br />" . $e->getFile() . ":" . $e->getLine());
        }
    }
}
