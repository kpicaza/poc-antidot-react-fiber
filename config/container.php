<?php

// Load configuration
use Antidot\Container\Builder;

$config = require __DIR__ . '/../config/config.php';

$config['factories'] = array_replace_recursive(
    $config['factories'],
    [\React\Http\Server::class => \App\Container\ServerFactory::class]
);

// Build container
return Builder::build($config, true);