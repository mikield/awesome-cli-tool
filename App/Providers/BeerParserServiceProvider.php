<?php

namespace App\Providers;

use App\Contracts\Parser;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class BeerParserServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple[Parser::class] = new class(
            $_ENV['API_KEY'],
            'https://sandbox-api.brewerydb.com/v2/'
        ) extends \Pintlabs_Service_Brewerydb implements Parser {};
    }

}