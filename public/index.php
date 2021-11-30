#!/usr/bin/env php
<?php

declare(strict_types=1);

use Antidot\Application\Http\Application;
use Psr\Log\LoggerInterface;
use React\EventLoop\Loop;
use React\Http\Server;
use React\Socket\Server as Socket;

require 'vendor/autoload.php';

(static function () {
    $container = require 'config/container.php';
    $application = $container->get(Application::class);
    (require 'router/middleware.php')($application, $container);
    (require 'router/routes.php')($application, $container);

    $serverInstance = static function () use ($container) {
        $server = $container->get(Server::class);
        $server->on('error', static function ($err) use ($container) {
            dump($err);
            $logger = $container->get(LoggerInterface::class);
            $logger->critical($err);
        });

        $socket = $container->get(Socket::class);
        $server->listen($socket);
    };

    $serverInstance();


    Loop::get()->run();
})();
