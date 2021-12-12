<?php

declare(strict_types=1);

namespace App\Test\Application\Http\Handler;

use App\Application\Features;
use App\Application\Http\Handler\ListToggles;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Feature;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class ListTogglesTest extends TestCase
{
    public function testHandleRequestAndReturnAListOfFeatures(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $featureFinder = $this->createMock(FeatureFinder::class);
        $feature1 = new Feature('some_feature_id', new ToggleStrategies(), false);
        $feature2 = new Feature('some_other_feature_id', new ToggleStrategies(), true);
        $featureFinder->expects(self::once())
            ->method('all')
            ->willReturn([$feature1, $feature2]);
        $featureFinder->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(['some_feature_id'], ['some_other_feature_id'])
            ->willReturnOnConsecutiveCalls($feature1, $feature2);
        $toggleRouter = new Toggle($featureFinder);
        $features = new Features(
            $toggleRouter,
            $featureFinder
        );

        $handler = new ListToggles(
            $features
        );

        $response = $handler->handle($request);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame(json_encode(['feature_toggles' => [
            [
                'featureId' => 'some_feature_id',
                'enabled' => false,
            ],
            [
                'featureId' => 'some_other_feature_id',
                'enabled' => true,
            ]
        ]]), $response->getBody()->getContents());
    }
}
