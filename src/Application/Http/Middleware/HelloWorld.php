<?php

declare(strict_types=1);

namespace App\Application\Http\Middleware;

use Antidot\React\PromiseResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Trowski\ReactFiber\FiberLoop;
use function React\Promise\resolve;

class HelloWorld implements MiddlewareInterface
{
    public function __construct(
        private FiberLoop $loop
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $greet = $this->loop->await(resolve($request->getQueryParams()['greet'] ?? 'World'));
        $request = $this->loop->await(resolve($request->withAttribute('greet', $greet)));

        return $handler->handle($request);
    }
}
