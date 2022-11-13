<?php

namespace Tests\Unit;

use App\Core\Router;
use App\Exceptions\RouteMethodNotAllowedException;
use App\Exceptions\RouteNotFoundException;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;
    private Container $container;

    private $controller;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->router = new Router($this->container);

        $this->controller = new class () {
            public function index(): string
            {
                return "usersPage";
            }
        };
    }

    public function test_that_it_registers_a_get_route(): void
    {
        $this->router->get("/users", ["Users", "index"]);

        $expected = [
            '/users' => [
                "get" => ["Users", "index"]
            ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function test_that_it_registers_a_post_route(): void
    {
        $this->router->post("/users", ["Users", "store"]);

        $expected = [
            '/users' => [
                "post" => ["Users", "store"]
            ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function test_that_there_are_no_routes_when_router_is_created(): void
    {
        $this->assertEmpty((new Router($this->container))->routes());
    }

    /**
     * @dataProvider \Tests\DataProviders\RouterDataProvider::routeNotFoundCases
     */
    public function test_that_it_throws_route_not_found_exception(string $route, string $method): void
    {
        $this->router->get("/users", [$this->controller::class, "index"]);

        $this->expectException(RouteNotFoundException::class);

        $this->router->resolve($route, $method);
    }

    /**
     * @dataProvider \Tests\DataProviders\RouterDataProvider::routeMethodNotAllowedCases
     */
    public function test_that_it_throws_route_method_not_allowed_exception(string $route, string $method): void
    {
        $this->router->get("/users", [$this->controller::class, "index"]);

        $this->expectException(RouteMethodNotAllowedException::class);

        $this->router->resolve($route, $method);
    }

    public function test_that_it_resolves_route_from_a_closure(): void
    {
        $this->router->get("/users", fn () => [1]);

        $this->assertEquals([1], $this->router->resolve("/users", "get"));
    }

    public function test_that_it_resolves_route(): void
    {
        $this->router->get("/users", [$this->controller::class, "index"]);

        $this->assertEquals("usersPage", $this->router->resolve("/users", "get"));
    }
}
