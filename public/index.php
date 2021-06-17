#!/usr/bin/env php
<?php

declare(strict_types=1);

use Antidot\Application\Http\Application;
use Antidot\React\Child;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Http\Server;
use React\Socket\Server as Socket;
use Trowski\ReactFiber\FiberLoop;

require 'vendor/autoload.php';

ini_set('memory_limit', '-1');

(static function () {
    $container = require 'config/container.php';
    $application = $container->get(Application::class);
    (require 'router/middleware.php')($application, $container);
    (require 'router/routes.php')($application, $container);
    $globalConfig = $container->get('config');
    $serverConfig = $globalConfig['server'];

    $loop = $container->get(FiberLoop::class);
    new \App\Container\Async($loop);
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

    if (1 < $serverConfig['workers']) {
        Child::fork($serverConfig['workers'], $serverInstance);
    } else {
        $serverInstance();
    }


    $loop->run();
})();
