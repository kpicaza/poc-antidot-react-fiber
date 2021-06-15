<?php

declare(strict_types=1);

namespace App\Container;

use Antidot\Application\Http\Application;
use Antidot\React\ReactApplication;
use Assert\Assertion;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Middleware\LimitConcurrentRequestsMiddleware;
use React\Http\Middleware\RequestBodyBufferMiddleware;
use React\Http\Middleware\RequestBodyParserMiddleware;
use React\Http\Middleware\StreamingRequestMiddleware;
use React\Http\Server;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Trowski\ReactFiber\FiberLoop;

final class ServerFactory
{
    public function __invoke(ContainerInterface $container): Server
    {
        $application = $container->get(Application::class);
        Assertion::isInstanceOf($application, ReactApplication::class);
        /** @var FiberLoop $loop */
        $loop = $container->get(FiberLoop::class);
        /** @var array<string, array> $globalConfig */
        $globalConfig = $container->get('config');
        /** @var array<string, int|null> $config */
        $config = $globalConfig['server'];
        Assertion::keyExists($config, 'max_concurrency');
        Assertion::keyExists($config, 'buffer_size');
        Assertion::integer($config['max_concurrency']);
        Assertion::integer($config['buffer_size']);

        $server = new Server(
            $loop,
            new StreamingRequestMiddleware(),
            new LimitConcurrentRequestsMiddleware($config['max_concurrency']),
            new RequestBodyBufferMiddleware($config['buffer_size']),
            new RequestBodyParserMiddleware(),
            static function(ServerRequestInterface $request) use ($application, $loop): PromiseInterface {
                return $loop->async(static function () use ($loop, $application, $request) {
                    return $loop->await(new Promise(
                        function (callable $resolve, callable $reject) use ($loop, $application, $request) {
                            try {
                                $resolve($application->handle($request));
                            } catch (\Throwable $exception) {
                                $reject($exception);
                            }
                        }
                    ));
                });
            }
        );

        return $server;
    }
}
    