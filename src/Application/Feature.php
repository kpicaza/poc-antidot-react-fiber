<?php

declare(strict_types=1);

namespace App\Application;

final class Feature
{
    public function __construct(
        public readonly string $featureId,
        public readonly bool $enabled
    ) {
    }
}
