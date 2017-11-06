<?php

namespace App\Providers\BeerFormatter;

use App\Contracts\Filesystem;
use App\Contracts\Formatter;
use App\Providers\BeerFormatter\Drivers\HtmlFormatterDriver;
use App\Providers\BeerFormatter\Drivers\JsonFormatterDriver;
use Pimple\Container;
use Pimple\Exception\InvalidServiceIdentifierException;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;


class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        if (!$pimple->offsetExists(Filesystem::class)) {
            throw new InvalidServiceIdentifierException('Filesystem is required to use Formatter');
        }
        $pimple[Formatter::class] = new class($pimple) implements Formatter
        {
            /** @var Filesystem $filesystem */
            private $filesystem;
            private $dir = '/';
            private $di;

            public function __construct(Container $pimple)
            {
                $this->di = $pimple;
                $this->filesystem = $pimple->offsetGet(Filesystem::class);
            }

            public function setOutputDir(string $dir)
            {
                $this->dir = $dir;
            }

            private $drivers = [
                'json' => JsonFormatterDriver::class,
                'html' => HtmlFormatterDriver::class
            ];

            public function format(string $type, $content)
            {
                if (!array_key_exists($type, $this->drivers)) {
                    throw new InvalidArgumentException("Type [$type] is not supported");
                }
                /** @var Driver $driver */
                $driver = new $this->drivers[$type]($this->di);
                $formatedContent = $driver->format($content);
                $filename = date("Y-m-d_H:i:s_") . "$type.$type";
                $this->filesystem->dumpFile($this->dir . '/' . $filename, $formatedContent);
                return $this->dir . '/' . $filename;
            }
        };
    }


    public static function boot(Container $pimple)
    {
        $pimple[Formatter::class]->setOutputDir($pimple->offsetExists('output_dir') ? $pimple->offsetGet('output_dir') : '/');
    }

}