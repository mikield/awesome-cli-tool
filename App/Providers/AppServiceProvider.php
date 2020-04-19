<?php

namespace App\Providers;

use Exception;
use Pimple\{Container, ServiceProviderInterface};
use Symfony\Component\Dotenv\Dotenv;

class AppServiceProvider implements ServiceProviderInterface
{
    private string $envFilePath;

    public function __construct($envFilePath)
    {
        $this->envFilePath = $envFilePath;
    }

    /**
     * Register service provider
     *
     * @param Container $pimple
     * @return void
     * @throws Exception
     */
    public function register(Container $pimple): void
    {
        if (!file_exists($this->envFilePath)) {
            throw new Exception('Env file is required');
        }

        $dotEnv = new Dotenv();
        $dotEnv->load($this->envFilePath);
    }

}