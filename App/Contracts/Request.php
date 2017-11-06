<?php

namespace App\Contracts;


interface Request
{
    /**
     * Set the base url for
     *
     * @param string $baseUrl
     * @return void
     */
    public function setBaseUrl(string $baseUrl);

    /**
     * Call a GET request to a url (or path if base url is set)
     *
     * @param string $url
     * @param array $getQuery
     * @return string
     */
    public function call(string $url, array $getQuery);

}