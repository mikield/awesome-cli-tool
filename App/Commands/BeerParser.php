<?php

namespace App\Commands;


use App\Contracts\Formatter;
use App\Contracts\Logger;
use App\Contracts\Request;
use Pimple\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;


class BeerParser extends Command
{

    /** @var array $beerIds Array of ids, that we will get from the API */
    private $beerIds = ['ATVPzT', 'bBKEzY', 'fw76Tc', 'ew5ryE', 'c9RcAi'];

    /** @var string $apiBaseUrl The API base url */
    private $apiBaseUrl = "http://api.brewerydb.com/v2/";

    /** @var Logger $logger Some console logger (cause we want candies in the output) */
    private $logger;

    /** @var Request $request The service that shall make the hard work */
    private $request;

    /** @var Formatter $formatter The result formatter */
    private $formatter;

    public function __construct(Container $container, $name = null)
    {
        /**
         * Getting the DI with Interface name - will protect us from mistakes in Provider naming (of course it can be a string) but to make a fake Dependency Invertion
         */
        $this->logger = $container->offsetGet(Logger::class);
        $this->request = $container->offsetGet(Request::class);
        $this->formatter = $container->offsetGet(Formatter::class);

        /**
         * Some basic configuration for the simplest request provider
         */
        $this->request->setBaseUrl($this->apiBaseUrl);
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
        /**
         * The request of course shall have some kind of field validation. For example I could say that the key value is required, and the Validation Exception should be thrown.
         */
        $response = $this->request->call('beers', [
            "ids" => implode(',', $this->beerIds),
            "format" => "php",
            "key" => "163b06d51ff21a62b466e63a2577b2a9"
        ]);
        $response = unserialize($response); // We shall unserialize the data, cause of format=php (API is sending it serialized)
        $helper = $this->getHelper('question');
        $types = ['json', 'html', 'xml', 'all'];
        $question = new ChoiceQuestion('What type will it be saved? (default: json)', $types, "json"); //What type do you want to use?
        $question->setErrorMessage('Type %s is invalid.');
        $type = $helper->ask($input, $output, $question);
        /**
         * If the user has chosen to save in all possible formats - walk thought them and save.
         * (In a perfect code it could be delegated to pools
         * (the saving process shall be async cause they are not related on each other)
         */
        if ($type == 'all') {
            unset($types['all']);
            foreach ($types as $type) {
                $this->saveResult($type, $response['data']);
            }
        } else {
            $this->saveResult($type, $response['data']);
        }
    }

    private function saveResult($type, $content)
    {
        $filename = $this->formatter->format($type, $content);
        $this->logger->log("The data is saved into: <path>$filename</path> 😉");
    }


}