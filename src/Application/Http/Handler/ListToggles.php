<?php

declare(strict_types=1);

namespace App\Application\Http\Handler;

use App\Application\Features;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ListToggles implements RequestHandlerInterface
{
    public function __construct(
        private Features $features
    ) {}


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = $request->getAttribute('identity');
        $payload = $request->getAttribute('payload');

        return new JsonResponse([
            'feature_toggles' => $this->features->all($identity, $payload)
        ]);
    }
}
    