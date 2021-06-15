<?php

// Load configuration
use Antidot\Container\Builder;

$config = require __DIR__ . '/../config/cli-config.php';

$config['factories'] = array_replace_recursive(
    $config['factories'],
    [\React\Http\Server::class => \App\Container\ServerFactory::class]
);

return Builder::build($config, true);
