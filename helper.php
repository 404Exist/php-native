<?php

if (!function_exists('dump')) {
    function dump(...$dumps)
    {
        foreach ($dumps as $dump) {
            echo "<pre style='background: #303030;color: #00ff00; padding: 10px; white-space: break-spaces;'>";
            print_r(is_callable($dump) ? $dump() : $dump);
            echo "</pre>";
        }
    }
}

if (!function_exists('dd')) {
    function dd(...$dumps)
    {
        dump(...$dumps);
        die(1);
    }
}

if (!function_exists('storage_path')) {
    function storage_path($file = '')
    {
        return __DIR__ . "/storage/$file";
    }
}

if (!function_exists('view')) {
    function view(string $view, array $params = [])
    {
        return \App\Core\View::make($view, $params);
    }
}

if (!function_exists('lazyRange')) {
    function lazyRange(int $start, int $end): \Generator
    {
        for ($i = $start; $i <= $end; $i++) {
            yield $i;
        }
    }
}

if (!function_exists('sendMail')) {
    function sendMail(
        Symfony\Component\Mime\RawMessage $message,
        Symfony\Component\Mailer\Envelope $envelope = null,
        string $dsn = null
    ) {
        return (new \App\Core\Mail($dsn))->send($message, $envelope);
    }
}
