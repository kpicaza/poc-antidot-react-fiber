<?php

declare(strict_types=1);

namespace App\Container;

use App\Infrastructure\FeatureUsingDriftDbal;
use Drift\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

final class FeaturesUsingDbalFactory
{
    public function __invoke(ContainerInterface $container): FeatureUsingDriftDbal
    {
        $connection = $container->get(Connection::class);
        Assert::isInstanceOf($connection, Connection::class);

        return new FeatureUsingDriftDbal(
            $connection
        );
    }
}
