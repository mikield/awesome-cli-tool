<?php

namespace App\Contracts;

use Symfony\Component\Filesystem\Exception\IOException;

interface Filesystem
{
    /**
     * Atomically dumps content into a file.
     *
     * @param string $filename The file to be written to
     * @param string $content The data to write into the file
     *
     * @throws IOException if the file cannot be written to
     */
    public function dumpFile($filename, $content);
}