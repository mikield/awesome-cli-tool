<?php

namespace App\Providers\BeerFormatter\Drivers;

use App\Providers\BeerFormatter\Driver;

class JsonFormatterDriver implements Driver
{
    /**
     * Format content into required style
     *
     * @param $content
     * @return string
     */
    public function format($content): string
    {
        $output = [];
        foreach ($content as $item) {
            $output[] = [
                'name' => array_key_exists('name', $item) ? $item['name'] : 'UNDEFINED',
                'description' => (array_key_exists('description', $item)) ? $item['description'] : 'UNDEFINED',
                'image' => (array_key_exists('labels', $item)) ? $item['labels']['large'] : 'UNDEFINED'
            ];
        }
        return json_encode($output);
    }
}