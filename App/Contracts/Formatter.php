<?php

namespace App\Contracts;

interface Formatter
{

    public function format(string $type, $content);

}