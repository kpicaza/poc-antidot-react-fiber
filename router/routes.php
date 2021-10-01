<?php

declare(strict_types=1);

use Antidot\Application\Http\Application;
use App\Application\Http\Handler\GetToggle;
use App\Application\Http\Handler\ListToggles;
use Psr\Container\ContainerInterface;

return static function (Application $app, ContainerInterface $container) : void {
    $app->get('/', [\App\Application\Http\Handler\PingHandler::class], 'ping');
    $app->get('/features', [ListToggles::class], 'get_features');
    $app->get('/features/{feature_id}', [GetToggle::class], 'get_feature_id');
};
