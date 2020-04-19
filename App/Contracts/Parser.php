<?php

namespace App\Contracts;

interface Parser
{
    /**
     * Sends a request using curl to the required endpoint
     *
     * @param string $endpoint The BreweryDb endpoint to use
     * @param array $args key value array of arguments
     *
     * @param $transferType
     * @return array
     */
    public function request($endpoint, $args = [], $transferType = "GET");

}