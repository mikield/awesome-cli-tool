<?php

namespace App\Contracts;

use Symfony\Component\Console\Output\OutputInterface;

interface Logger
{
    public function setOutput(OutputInterface $output);
    public function log(string $message);

}