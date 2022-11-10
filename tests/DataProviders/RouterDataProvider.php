<?php

namespace Tests\DataProviders;

class RouterDataProvider
{
    public function routeNotFoundCases(): array
    {
        return [
            ["/home", "get"],
        ];
    }

    public function routeMethodNotAllowedCases(): array
    {
        return [
            ["/users", "put"],
            ["/users", "delete"],
        ];
    }
}
