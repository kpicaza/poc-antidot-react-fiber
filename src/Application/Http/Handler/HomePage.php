<?php

declare(strict_types=1);

namespace App\Application\Http\Handler;

use App\Application\Event\SomeEvent;
use App\Container\Async;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function React\Promise\resolve;

final class HomePage implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $message = Async::await(resolve(sprintf(
            'Hello %s!!!! Welcome to Antidot Framework Starter',
            Async::await(resolve($request->getAttribute('greet')))
        )));

        return new JsonResponse([
            'docs' => Async::await(resolve('https://antidotfw.io')),
            'message' => $message
        ]);
    }
}
