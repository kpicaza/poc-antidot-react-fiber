<?php

declare(strict_types=1);

namespace App\Container;

use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;
use Trowski\ReactFiber\FiberLoop;

final class StreamingRequestFiberMiddleware
{
    public function __construct(
        private FiberLoop $loop
    ) {}

    public function __invoke(ServerRequestInterface $request, callable $next): PromiseInterface
    {
        return $this->loop->async(static fn() => $next($request));
    }
}
    