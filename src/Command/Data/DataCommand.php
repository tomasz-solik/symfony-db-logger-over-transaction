<?php

namespace App\Command\Data;

use App\Service\Data\DataService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DataCommand extends Command
{
    protected static $defaultName = 'data:data';
    protected static $defaultDescription = 'Add a short description for your command';
    /**
     * @var DataService
     */
    private $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $result = $this->dataService->insert('Data input: '.time());
        if ($result) {
            $io->success('success');
        } else {
            $io->error('error');
        }

        return Command::SUCCESS;
    }
}
