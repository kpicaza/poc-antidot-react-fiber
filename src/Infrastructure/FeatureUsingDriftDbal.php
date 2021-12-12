<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Drift\DBAL\Connection;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Identity;
use Webmozart\Assert\Assert;
use function React\Async\await;

final class FeatureUsingDriftDbal implements FeatureFinder
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function all(?Identity $identity = null): array
    {
        $result = await($this->connection->findBy('pheature_toggles', []));
        Assert::isArray($result);

        $features = [];
        foreach ($result as $row) {
            $features[] = self::hydrateFeature($this->parseRow($row));
        }

        return $features;
    }

    public function get(string $featureId): Feature
    {
        $row = await($this->connection->findOneBy(
            'pheature_toggles',
            ['feature_id' => $featureId]
        ));

        return self::hydrateFeature($this->parseRow($row));
    }

    /**
     * @param array{feature_id: string, enabled: bool} $dbRow
     */
    private static function hydrateFeature(array $dbRow): Feature
    {
        return new \Pheature\Model\Toggle\Feature(
            $dbRow['feature_id'],
            new ToggleStrategies(),
            $dbRow['enabled']
        );
    }

    /**
     * @param mixed $row
     * @return array{feature_id: string, enabled: bool}
     */
    private function parseRow(mixed $row): array
    {
        Assert::isArray($row);
        Assert::keyExists($row, 'feature_id');
        Assert::string($row['feature_id']);
        Assert::keyExists($row, 'enabled');
        $row['enabled'] = (bool)$row['enabled'];

        return $row;
    }
}
