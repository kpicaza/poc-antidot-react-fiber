<?php

declare(strict_types=1);

namespace App\Application;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Model\Toggle\Identity;

final class Features
{
    public function __construct(
        private Toggle $toggleRouter,
        private FeatureFinder $featureFinder
    ) {
    }

    public function all(?string $identity = null, ?array $payload = null): array
    {
        $featureToggles = [];

        $features = $this->featureFinder->all();
        foreach ($features as $feature) {
            $featureToggles[] = new Feature(
                $feature->id(),
                $this->toggleRouter->isEnabled(
                    $feature->id(),
                    $identity ? new Identity($identity, $payload) : null
                )
            );
        }

        return $featureToggles;
    }

    public function byId(string $featureId, ?string $identity = null, ?array $payload = null): Feature
    {
        return new Feature(
            $featureId,
            $this->toggleRouter->isEnabled(
                $featureId,
                $identity ? new Identity($identity, $payload) : null
            )
        );
    }
}
