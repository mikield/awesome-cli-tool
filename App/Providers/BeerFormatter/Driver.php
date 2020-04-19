<?php

namespace App\Providers\BeerFormatter;

interface Driver
{
    /**
     * Format content into required style
     *
     * @param $content
     * @return string
     */
    public function format($content): string;
}