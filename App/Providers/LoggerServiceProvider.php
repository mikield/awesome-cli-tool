<?php

namespace App\Providers;

use App\Contracts\Logger;
use Pimple\{Container, ServiceProviderInterface};
use Symfony\Component\Console\ {Formatter\OutputFormatterStyle, Output\OutputInterface};

class LoggerServiceProvider implements ServiceProviderInterface
{

    /**
     * Register service provider
     *
     * @param Container $pimple
     * @return void
     */
    public function register(Container $pimple): void
    {
        /**
         *  Just a beautifier for console outputs
         *  Because Symfony console component has build in error handler :)
         */
        $pimple[Logger::class] = new class implements Logger {
            private OutputInterface $output;

            /**
             * Set Logger output
             *
             * @param OutputInterface $output
             * @return mixed
             */
            public function setOutput(OutputInterface $output)
            {
                $this->output = $output;
                $this->attachStyles();
            }

            /**
             * Log a message
             *
             * @param string $message
             * @return mixed
             */
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