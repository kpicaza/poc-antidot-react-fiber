<?php

declare(strict_types=1);

namespace App\Container;

use App\Infrastructure\FeatureUsingDriftDbal;
use Drift\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Trowski\ReactFiber\FiberLoop;

final class FeaturesUsingDbalFactory
{
    public function __invoke(ContainerInterface $container): FeatureUsingDriftDbal
    {
        return new FeatureUsingDriftDbal(
            $container->get(Connection::class),
            $container->get(FiberLoop::class)
        );
    }
}
