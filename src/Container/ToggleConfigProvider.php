<?php

declare(strict_types=1);

namespace App\Container;

use App\Infrastructure\FeatureUsingDriftDbal;
use Drift\DBAL\Connection;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr11\Toggle\ChainToggleStrategyFactoryFactory;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\SegmentFactoryFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrategyFactoryFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;
use function array_merge;
use function array_reduce;

final class ToggleConfigProvider
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        $pheatureFlagsConfig = $this->pheatureFlagsConfig();

        /** @var array<array<string, string>> $strategyTypes */
        $strategyTypes = $pheatureFlagsConfig['strategy_types'];

        $strategyTypeAliases = array_reduce(
            $strategyTypes,
            static function (array $strategies, array $current) {
                $strategies[(string) $current['type']] = (string) $current['factory_id'];

                return $strategies;
            },
            []
        );

        /** @var array<array<string, string>> $segmentTypes */
        $segmentTypes = $pheatureFlagsConfig['segment_types'];

        $segmentTypeAliases = array_reduce(
            $segmentTypes,
            static function (array $segments, array $current) {
                $segments[(string) $current['type']] = (string) $current['factory_id'];

                return $segments;
            },
            []
        );

        return [
            'aliases' => array_merge($strategyTypeAliases, $segmentTypeAliases),
            'services' => [
                FeatureFinder::class => FeatureUsingDriftDbal::class,
            ],
            'factories' => [
                FeatureUsingDriftDbal::class => FeaturesUsingDbalFactory::class,
                // Read model
                StrategyFactory::class => StrategyFactoryFactory::class,
                SegmentFactory::class => SegmentFactoryFactory::class,
                ChainToggleStrategyFactory::class => ChainToggleStrategyFactoryFactory::class,
                Connection::class => ConnectionFactory::class,
            ],
            'pheature_flags' => $pheatureFlagsConfig,
        ];
    }

    /** @return array<string,mixed> */
    private function pheatureFlagsConfig(): array
    {
        return [
            'api_enabled' => false,
            'api_prefix' => '',
            'driver' => 'dbal',
            'segment_types' => [
                [
                    'type' => IdentitySegment::NAME,
                    'factory_id' => SegmentFactory::class
                ],
                [
                    'type' => StrictMatchingSegment::NAME,
                    'factory_id' => SegmentFactory::class
                ]
            ],
            'strategy_types' => [
                [
                    'type' => EnableByMatchingSegment::NAME,
                    'factory_id' => StrategyFactory::class
                ],
                [
                    'type' => EnableByMatchingIdentityId::NAME,
                    'factory_id' => StrategyFactory::class
                ],
            ],
            'toggles' => [],
        ];
    }
}
