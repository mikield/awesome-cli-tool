<?php

namespace App\Contracts;

interface Formatter
{

    public function format(string $type, $content);

    public function getTypes(): array;

    public function setOutputDir(string $dir): void;

}