<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Drift\DBAL\Connection;
use JetBrains\PhpStorm\Pure;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Identity;
use function React\Async\await;

final class FeatureUsingDriftDbal implements FeatureFinder
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function all(?Identity $identity = null): array
    {
        $result = await($this->connection->queryBySQL(
            <<<SQL
                SELECT * FROM pheature_toggles
            SQL
        ));
        $features = [];
        foreach ($result as $row) {
            $features[] = self::hydrateFeature($row);
        }

        return $features;
    }

    public function get(string $featureId): Feature
    {
        $row = await($this->connection->findOneBy(
            'pheature_toggles',
            ['feature_id' => $featureId]
        ));

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
