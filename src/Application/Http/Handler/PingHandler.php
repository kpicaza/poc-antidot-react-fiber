<?php

declare(strict_types=1);

namespace App\Application\Http\Handler;

use App\Container\Async;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function React\Async\await;
use function React\Promise\resolve;

final class PingHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['ping' => await(resolve('pong'))]);
    }
}
