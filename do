#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Pimple\Container;
use Symfony\Component\Console\Application;

/** Create a container for Service Provider */
$container = new Container();

/** Registering some Service Providers */
$container->register(new \App\Providers\LoggerServiceProvider);
$container->register(new \App\Providers\RequestServiceProvider);
$container->register(new \App\Providers\FilesystemServiceProvider);
$container->register(new \App\Providers\BeerFormatter\ServiceProvider(), [
    'output_dir' => __DIR__ . '/tmp'
]);

/** A simple booting or BeerFormatter\ServiceProvider (cause the Console application or a DI has no booting method) */
\App\Providers\BeerFormatter\ServiceProvider::boot($container);


$app = new Application('Awesome CLI Tool', '0.5b'); //Fake version (b - meaning beta)

$app->addCommands([
    new App\Commands\BeerParser($container)
]);

$app->run();