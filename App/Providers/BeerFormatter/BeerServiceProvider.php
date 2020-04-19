<?php

namespace App\Providers\BeerFormatter;

use App\Contracts\ {Filesystem, Formatter};
use App\Providers\BeerFormatter\Drivers\ {HtmlFormatterDriver, JsonFormatterDriver};
use Pimple\ {Container, Exception\InvalidServiceIdentifierException, ServiceProviderInterface};
use Symfony\Component\Console\Exception\InvalidArgumentException;

class BeerServiceProvider implements ServiceProviderInterface
{
    /**
     * Register service provider
     *
     * @param Container $pimple
     * @return void
     */
    public function register(Container $pimple): void
    {
        if (!$pimple->offsetExists(Filesystem::class)) {
            throw new InvalidServiceIdentifierException('Filesystem is required to use Formatter');
        }

        $pimple[Formatter::class] = function (Container $container) {
            return new class($container) implements Formatter {

                const JSON_TYPE = 'json';
                const HTML_TYPE = 'html';
                const ALL_TYPE = 'all';

                /** @var Filesystem $filesystem */
                private $filesystem;

                /** @var string */
                private $dir;

                /** @var Driver[] */
                private $drivers = [
                    self::JSON_TYPE => JsonFormatterDriver::class,
                    self::HTML_TYPE => HtmlFormatterDriver::class
                ];

                /**
                 * @var Container
                 */
                private Container $pimple;

                public function __construct(Container $pimple)
                {
                    $this->filesystem = $pimple->offsetGet(Filesystem::class);
                    $this->dir = $pimple->offsetExists('output_dir') ? $pimple->offsetGet('output_dir') : '/';
                    $this->pimple = $pimple;
                }

                /**
                 * Set output dir path
                 *
                 * @param string $dir
                 */
                public function setOutputDir(string $dir): void
                {
                    $this->dir = $dir;
                }

                /**
                 * Get available types
                 *
                 * @return array
                 */
                public function getTypes(): array
                {
                    return [self::ALL_TYPE, ...array_keys($this->drivers)];
                }

                /**
                 * Format content to selected type
                 *
                 * @param string $type
                 * @param $content
                 * @return mixed
                 */
                public function format(string $type, $content)
                {
                    if ($type === self::ALL_TYPE) {
                        $filePaths = [];
                        foreach ($this->drivers as $type => $driver) {
                            $driver = new $driver($this->pimple);
                            $filePaths[] = $this->formatUsingDriver($driver, $type, $content);
                        }
                        return $filePaths;
                    }

                    if (!array_key_exists($type, $this->drivers)) {
                        throw new InvalidArgumentException("Type [$type] is not supported");
                    }

                    $driver = new $this->drivers[$type]($this->pimple);
                    return $this->formatUsingDriver($driver, $type, $content);
                }

                private function formatUsingDriver(Driver $driver, string $type, $content): string
                {
                    $filename = date("Y-m-d_H:i:s_") . ".$type";
                    $this->filesystem->dumpFile($this->dir . '/' . $filename, $driver->format($content));
                    return $this->dir . '/' . $filename;
                }
            };
        };
    }
}


