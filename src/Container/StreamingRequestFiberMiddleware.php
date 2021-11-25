<?php

declare(strict_types=1);

namespace App\Container;

use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;
use function React\Async\async;

final class StreamingRequestFiberMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): PromiseInterface
    {
        return async(static fn() => $next($request));
    }
}
