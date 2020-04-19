#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use App\Providers\AppServiceProvider;
use App\Providers\BeerFormatter\BeerServiceProvider;
use App\Providers\BeerParserServiceProvider;
use App\Providers\FilesystemServiceProvider;
use App\Providers\LoggerServiceProvider;
use Pimple\Container;
use Symfony\Component\Console\Application;

/** Create a container for Service Provider */
$container = new Container();

/** Registering some Service Providers */
$container->register(new AppServiceProvider(__DIR__.'/.env'));
$container->register(new LoggerServiceProvider);
$container->register(new BeerParserServiceProvider());
$container->register(new FilesystemServiceProvider);
$container->register(new BeerServiceProvider(), [
    'output_dir' => __DIR__ . '/tmp'
]);

$app = new Application('Beer parser CLI', '1'); //Fake version (b - meaning beta)

$app->add(new App\Commands\BeerParser($container));

$app->run();