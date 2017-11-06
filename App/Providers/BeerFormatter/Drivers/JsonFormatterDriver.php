<?php

namespace App\Providers\BeerFormatter\Drivers;

use App\Providers\BeerFormatter\Driver;

class JsonFormatterDriver implements Driver
{

    public function format($content)
    {
        $output = [];
        foreach ($content as $item) {
            $output[] = [
                'name' => (array_key_exists('name', $item)) ? $item['name'] : '',
                'description' => (array_key_exists('description', $item)) ? $item['description'] : '',
                'image' => (array_key_exists('labels', $item)) ? $item['labels']['large'] : ''
            ];
        }
        return json_encode($output);
    }
}