<?php

declare(strict_types=1);

namespace App\Container;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Drift\DBAL\Connection;
use Drift\DBAL\Credentials;
use Drift\DBAL\Driver\PostgreSQL\PostgreSQLDriver;
use Psr\Container\ContainerInterface;
use React\EventLoop\Loop;

final class ConnectionFactory
{
    public function __invoke(ContainerInterface $container): Connection
    {
        $mysqlPlatform = new PostgreSQL100Platform();
        $mysqlDriver = new PostgreSQLDriver(Loop::get());
        $credentials = new Credentials(
            'postgres',
            '5432',
            'postgres',
            'secret',
            'postgres'
        );

        return Connection::createConnected(
            $mysqlDriver,
            $credentials,
            $mysqlPlatform
        );
    }
}
