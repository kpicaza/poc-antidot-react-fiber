<?php

declare(strict_types=1);

namespace App\Test\Application\Http\Handler;

use App\Application\Features;
use App\Application\Http\Handler\GetToggle;
use Generator;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class GetToggleTest extends TestCase
{
    /** @dataProvider getInvalidRequest */
    public function testHandleInvalidRequestAndReturnErrorResponse(ServerRequestInterface $request): void
    {
        $featureFinder = $this->createMock(FeatureFinder::class);
        $toggleRouter = new Toggle($featureFinder);
        $features = new Features(
            $toggleRouter,
            $featureFinder
        );

        $handler = new GetToggle(
            $features
        );

        $response = $handler->handle($request);
        self::assertSame(400, $response->getStatusCode());

    }

    public function testHandleRequestAndReturnAToggleResult(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::exactly(3))
            ->method('getAttribute')
            ->withConsecutive(['feature_id'], ['identity'], ['payload'])
            ->willReturnOnConsecutiveCalls('some_feature_id', null, []);

        $featureFinder = $this->createMock(FeatureFinder::class);
        $toggleRouter = new Toggle($featureFinder);
        $features = new Features(
            $toggleRouter,
            $featureFinder
        );

        $handler = new GetToggle(
            $features
        );

        $response = $handler->handle($request);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame(['application/json'], $response->getHeader('content-type'));
        self::assertSame(json_encode([
            'featureId' => 'some_feature_id',
            'enabled' => false
        ]), $response->getBody()->getContents());
    }

    public function getInvalidRequest(): Generator
    {
        yield 'Request without any attributes' => [
            $this->createMock(ServerRequestInterface::class)
        ];

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::exactly(2))
            ->method('getAttribute')
            ->withConsecutive(['feature_id'], ['identity'])
            ->willReturnOnConsecutiveCalls('some_feature_id', 7898698);
        yield 'Request with invalid Identity' => [
            $request
        ];

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::exactly(2))
            ->method('getAttribute')
            ->withConsecutive(['feature_id'], ['identity'])
            ->willReturnOnConsecutiveCalls('some_feature_id', '');
        yield 'Request with empty Identity string' => [
            $request
        ];

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::exactly(3))
            ->method('getAttribute')
            ->withConsecutive(['feature_id'], ['identity'], ['payload'])
            ->willReturnOnConsecutiveCalls('some_feature_id', 'some_identity', '');
        yield 'Request with invalid payload' => [
            $request
        ];
    }
}
