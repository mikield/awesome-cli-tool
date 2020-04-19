<?php

namespace App\Providers;

use App\Contracts\Parser;
use Pimple\{Container, ServiceProviderInterface};

class BeerParserServiceProvider implements ServiceProviderInterface
{

    /**
     * Register service provider
     *
     * @param Container $pimple
     * @return void
     */
    public function register(Container $pimple): void
    {
        $pimple[Parser::class] = new class(
            $_ENV['API_KEY'],
            'https://sandbox-api.brewerydb.com/v2/'
        ) extends \Pintlabs_Service_Brewerydb implements Parser {};
    }

}