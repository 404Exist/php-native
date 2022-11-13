<?php

namespace App\Core;

use App\Examples\Interfaces\PaymentGatewayInterface;
use App\Examples\Services\StripePayment;
use App\Exceptions\RouteNotFoundException;
use App\Exceptions\ViewNotFoundException;
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Symfony\Component\Mailer\MailerInterface;

class App
{
    public function __construct(
        protected Container $container,
        protected Router $router,
    ) {
    }

    public function initDB()
    {
        $capsule = new Capsule();

        $capsule->addConnection((new Config())->db);

        $capsule->setEventDispatcher(new Dispatcher($this->container));

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }

    public function boot(): static
    {
        session_start();

        $dotenv = Dotenv::createImmutable(__DIR__ . "/../..");
        $dotenv->load();

        $this->initDB();

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
