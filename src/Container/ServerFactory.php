<?php

declare(strict_types=1);

namespace App\Container;

use Antidot\Application\Http\Application;
use Antidot\React\ReactApplication;
use Assert\Assertion;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Loop;
use React\Http\Middleware\LimitConcurrentRequestsMiddleware;
use React\Http\Middleware\RequestBodyBufferMiddleware;
use React\Http\Middleware\RequestBodyParserMiddleware;
use React\Http\Server;

final class ServerFactory
{
    public function __invoke(ContainerInterface $container): Server
    {
        $application = $container->get(Application::class);
        Assertion::isInstanceOf($application, ReactApplication::class);
        /** @var array<string, array> $globalConfig */
        $globalConfig = $container->get('config');
        /** @var array<string, int|null> $config */
        $config = $globalConfig['server'];
        Assertion::keyExists($config, 'max_concurrency');
        Assertion::keyExists($config, 'buffer_size');
        Assertion::integer($config['max_concurrency']);
        Assertion::integer($config['buffer_size']);

        $server = new Server(
            Loop::get(),
            new StreamingRequestFiberMiddleware(),
            new LimitConcurrentRequestsMiddleware($config['max_concurrency']),
            new RequestBodyBufferMiddleware($config['buffer_size']),
            new RequestBodyParserMiddleware(),
            static fn (ServerRequestInterface $request) => $application->handle($request)
        );

        return $server;
    }
}
