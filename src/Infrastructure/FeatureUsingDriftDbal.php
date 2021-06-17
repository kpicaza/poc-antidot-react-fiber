<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Drift\DBAL\Connection;
use Drift\DBAL\Result;
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
    ) {}

    public function all(?Identity $identity = null): array
    {
        /** @var Result $result */
        $result = $this->loop->await(
            $this->connection->queryBySQL(
                <<<SQL
                    SELECT * FROM pheature_toggles
                SQL
            )
        );

        $features = [];
        foreach ($result->fetchAllRows() as $row) {
            $features[] = $row;
        }

        return array_map([self::class, 'hydrateFeature'], $features);
    }

    public function get(string $featureId): Feature
    {
        $row = $this->loop->await(
            $this->connection->findOneBy(
                'pheature_toggles',
                ['feature_id' => $featureId]
            )
        );

        return self::hydrateFeature($row);
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
    