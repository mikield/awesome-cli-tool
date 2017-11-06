<?php

namespace App\Providers;

use App\Contracts\Request;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RequestServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        /**
         * The simples request than even could be.
         * It could be of course Guzzle and other hard stuff, as far as DI will check the implementation of a Contract -
         * replacing the service provider should be secure.
         */
        $pimple[Request::class] = new class implements Request
        {
            /** @var string $baseUrl The basic url for the request */
            private $baseUrl = '';

            /**
             * @param string $baseUrl
             */
            public function setBaseUrl(string $baseUrl)
            {
                $this->baseUrl = $baseUrl;
            }

            /**
             * Call a GET request to a url (or path if base url is set)
             *
             * @param string $url
             * @param array $getQuery
             * @return string
             * @throws \HttpRequestException
             */
            public function call(string $url, array $getQuery)
            {
                $req = file_get_contents($this->baseUrl . $url . '?' . http_build_query($getQuery));
                if ($req === false) {
                    throw new \HttpRequestException('There were a error with the request');
                }
                return $req;
            }
        };

    }

}