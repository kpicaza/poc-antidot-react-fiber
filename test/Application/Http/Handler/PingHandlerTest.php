<?php

declare(strict_types=1);

namespace App\Test\Application\Http\Handler;

use App\Application\Http\Handler\PingHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class PingHandlerTest extends TestCase
{

    public function testHandlePingRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $handler = new PingHandler();
        $response = $handler->handle($request);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame(['application/json'], $response->getHeader('content-type'));
        self::assertSame(json_encode(['ping' => 'pong']), $response->getBody()->getContents());
    }
}
