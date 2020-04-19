<?php

namespace App\Contracts;

interface Formatter
{
    /**
     * Format content to selected type
     *
     * @param string $type
     * @param $content
     * @return mixed
     */
    public function format(string $type, $content);

    /**
     * Get available types
     *
     * @return array
     */
    public function getTypes(): array;

    /**
     * Set output dir path
     *
     * @param string $dir
     */
    public function setOutputDir(string $dir): void;

}