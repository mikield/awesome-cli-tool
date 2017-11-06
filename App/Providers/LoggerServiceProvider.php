<?php

namespace App\Providers;

use App\Contracts\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class LoggerServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        /**
         *  Just a beautifier for console outputs
         *  Because Symfony console component has build in error handler :)
         */
        $pimple[Logger::class] = new class implements Logger
        {
            /** @var  OutputInterface $output */
            protected $output;

            public function setOutput(OutputInterface $output)
            {
                $this->output = $output;
                $this->attachStyles();
            }

            public function log(string $message)
            {
                $this->output->writeLn('<time>[' . date("Y-m-d H:i:s") . ']</time> ' . $message);
            }

            private function attachStyles()
            {
                $timeStyle = new OutputFormatterStyle('yellow', null, array('bold', 'blink'));
                $this->output->getFormatter()->setStyle('time', $timeStyle);

                $arrayStyle = new OutputFormatterStyle('black', null, array('bold', 'blink'));
                $this->output->getFormatter()->setStyle('array', $arrayStyle);

                $pathStyle = new OutputFormatterStyle('green', null, array('bold', 'blink'));
                $this->output->getFormatter()->setStyle('path', $pathStyle);

            }
        };

    }

}