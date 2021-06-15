<?php

declare(strict_types=1);

namespace App\Application\Http\Middleware;

use App\Container\Async;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function React\Promise\resolve;

class HelloWorld implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $greet = Async::await(resolve($request->getQueryParams()['greet'] ?? 'World'));
        $request = Async::await(resolve($request->withAttribute('greet', $greet)));

        return $handler->handle($request);
    }
}
