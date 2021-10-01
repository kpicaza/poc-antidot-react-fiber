<?php

declare(strict_types=1);

namespace App\Application\Http\Handler;

use App\Application\Features;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetToggle implements RequestHandlerInterface
{
    public function __construct(
        private Features $features
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $featureId = $request->getAttribute('feature_id');
        $identity = $request->getAttribute('identity');
        $payload = $request->getAttribute('payload');

        return new JsonResponse($this->features->byId($featureId, $identity, $payload));
    }
}
