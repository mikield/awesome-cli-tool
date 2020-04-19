<?php

namespace App\Commands;


use App\Contracts\Formatter;
use App\Contracts\Logger;
use App\Contracts\Parser;
use App\Contracts\Request;
use Pimple\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;


class BeerParser extends Command
{

    /** @var array $beerIds Array of ids, that we will get from the API */
    private array $beerIds = ['aG4Ie2', '2cLm8B', 'RK9Po6'];

    private Logger $logger;

    private Formatter $formatter;

    private Parser $parser;

    public function __construct(Container $container, $name = null)
    {
        /**
         * Getting the DI with Interface name - will protect us from mistakes in Provider naming (of course it can be a string) but to make a fake Dependency Inversion
         */
        $this->logger = $container->offsetGet(Logger::class);
        $this->formatter = $container->offsetGet(Formatter::class);
        $this->parser = $container->offsetGet(Parser::class);

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('parse')
            ->setDescription('Parse a list of beers from a API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->setOutput($output);

        $this->logger->log('Lets start the party 😊');
        $this->logger->log('Taking info about this beers: <array>[' . implode(', ', $this->beerIds) . ']</array>');

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion('What type will it be saved? (default: all)', $this->formatter->getTypes(), "all"); //What type do you want to use?
        $question->setErrorMessage('Type %s is invalid.');
        $type = $helper->ask($input, $output, $question);

        $response = $this->parser->request('beers', [
            'ids' => implode(",", $this->beerIds)
        ]);

        $filename = $this->formatter->format($type, $response['data']);
        $this->logger->log("The data is saved into: \n<path>". implode("\n", $filename) ."</path> 😉");
    }
}
