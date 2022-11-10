<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Attributes\Get;
use App\Core\Attributes\Post;
use App\Core\DB;
use App\Models\User;
use Symfony\Component\Mime\Email;

class UsersController
{
    #[Get("/users")]
    public function users()
    {
        $nollywood = DB::instance(["database" => "nollywood"]);

        $user = new User();

        // App::db()->transaction(function () use ($user) {
        //     $user->create([
        //         "email" => "user6@user.com",
        //         "full_name" => "Obi User",
        //         "created_at" => date("Y-m-d H:i:s"),
        //     ]);
        //     $user->create([
        //         "osama" => "saad",
        //         "mohamed" => 2
        //     ]);
        // });
        dd(
            App::db()->query("SELECT * FROM users")->get(),
            $user->find(1),
            "nollywood users count is: " . $nollywood->query("SELECT * FROM users")->count()
        );
    }

    #[Post("/users")]
    public function register()
    {
        $email = (new Email())
            ->from("osamasaad1237@gmail.com")
            ->to("test@test.test")
            ->subject("Welcome")
            ->html(view("home")->render());


        sendMail($email);
    }
}
