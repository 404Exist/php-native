<?php

namespace App\Controllers;

use App\Core\Attributes\Get;
use App\Core\Attributes\Post;
use App\Core\View;
use App\Examples\Services\InvoiceService;

class HomeController
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    #[Get("/")]
    #[Get("/home")]
    public function index(): View
    {
        $this->invoiceService->process([], 25);

        return view("home");
    }

    #[Post("/")]
    public function store()
    {
        $file_path = storage_path($_FILES['file']['name']);

        dd(
            $_POST,
            $_FILES,
            $file_path
        );
        // move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    }
}
