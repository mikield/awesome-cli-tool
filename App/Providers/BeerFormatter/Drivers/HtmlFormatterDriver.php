<?php

namespace App\Providers\BeerFormatter\Drivers;

use App\Providers\BeerFormatter\Driver;

class HtmlFormatterDriver implements Driver
{
    /**
     * Format content into required style
     *
     * @param $content
     * @return string
     */
    public function format($content): string
    {
        $output = "<h1>Result: </h1>";
        $output .= "<table><tr><th>Name</th><th>Description</th><th>Image</th></tr>";

        foreach ($content as $item) {
            $name = array_key_exists('name', $item) ? $item['name'] . '|' : '';
            $desc = array_key_exists('description', $item) ? $item['description'] . '|' : '';
            $image = array_key_exists('labels', $item) ? $item['labels']['large'] : '';
            $output .= "<tr>";
            $output .= "<th>" . $name . "</th>";
            $output .= "<th>" . $desc . "</th>";
            $output .= "<th><img src=" . $image . "></th>";
            $output .= "</tr>";
        }

        $output .= "</table>";
        return $output;
    }
}