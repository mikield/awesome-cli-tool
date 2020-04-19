<?php

namespace App\Contracts;

use Symfony\Component\Console\Output\OutputInterface;

interface Logger
{
    /**
     * Set Logger output
     *
     * @param OutputInterface $output
     * @return mixed
     */
    public function setOutput(OutputInterface $output);

    /**
     * Log a message
     *
     * @param string $message
     * @return mixed
     */
    public function log(string $message);

}