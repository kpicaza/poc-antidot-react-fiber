<?php

declare(strict_types=1);

namespace App\Container;

use Psr\Container\ContainerInterface;
use React\EventLoop\LoopInterface;
use Trowski\ReactFiber\FiberLoop;

final class FiberLoopFactory
{
    public function __invoke(ContainerInterface $container): LoopInterface
    {
        return new FiberLoop($container->get(LoopInterface::class));
    }
}
