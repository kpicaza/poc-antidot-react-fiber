<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Drift\DBAL\Connection;
use Drift\DBAL\Result;
use Generator;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Identity;
use Trowski\ReactFiber\FiberLoop;

final class FeatureUsingDriftDbal implements FeatureFinder
{
    public function __construct(
        private Connection $connection,
        private FiberLoop $loop
    ) {
    }

    public function all(?Identity $identity = null): array
    {
        return $this->loop->await(
            $this->connection->queryBySQL(
                <<<SQL
                    SELECT * FROM pheature_toggles
                SQL
            )->then(
                fn(Result $result): Generator => yield from $result->fetchAllRows()
            )->then(function (Generator $result): array {
                $features = [];
                foreach ($result as $row) {
                    $features[] = self::hydrateFeature($row);
                }

                return $features;
            })
        );
    }

    public function get(string $featureId): Feature
    {
        return $this->loop->await(
            $this->connection->findOneBy(
                'pheature_toggles',
                ['feature_id' => $featureId]
            )->then(
                fn(array $row): Feature => self::hydrateFeature($row)
            )
        );
    }

    #[Pure]
    private static function hydrateFeature(array $dbRow): Feature
    {
        return new \Pheature\Model\Toggle\Feature(
            $dbRow['feature_id'],
            new ToggleStrategies(),
            (bool)$dbRow['enabled']
        );
    }
}
