<?php

declare(strict_types=1);

namespace App\Container;

use React\Promise\PromiseInterface;
use Trowski\ReactFiber\FiberLoop;

final class Async
{
    private static FiberLoop $loop;

    public function __construct(FiberLoop $fiberLoop)
    {
        static::$loop = $fiberLoop;
    }

    public static function await(PromiseInterface $promise): mixed
    {
        $loop = static::$loop;

        return $loop->await($promise);
    }
}
