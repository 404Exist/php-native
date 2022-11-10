<?php

namespace App\Core;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        protected string $view,
        protected array $params = []
    ) {
    }

    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string
    {
        if (! file_exists($this->viewFile())) {
            throw new ViewNotFoundException();
        }

        extract($this->params);

        ob_start();

        include $this->viewFile();

        return (string) ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    protected function viewFile()
    {
        return __DIR__ . "/../../resources/views/" . $this->view . ".php";
    }
}
