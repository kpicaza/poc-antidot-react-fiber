<?php

declare(strict_types=1);

namespace App\Application\Http\Handler;

use App\Application\Features;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\Assert;

final class ListToggles implements RequestHandlerInterface
{
    public function __construct(
        private Features $features
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $identity = $request->getAttribute('identity');
            Assert::nullOrStringNotEmpty($identity);
            $payload = $request->getAttribute('payload');
            Assert::nullOrIsArray($payload);
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }

        return new JsonResponse([
            'feature_toggles' => $this->features->all($identity, $payload)
        ]);
    }
}
