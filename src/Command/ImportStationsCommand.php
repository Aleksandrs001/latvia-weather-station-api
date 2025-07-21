<?php

namespace App\Command;

use App\Service\StationImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportStationsCommand extends Command
{
    protected static $defaultName = 'app:import-stations';

    private StationImporter $importer;

    public function __construct(StationImporter $importer)
    {
        parent::__construct();
        $this->importer = $importer;
    }

    protected function configure()
    {
        $this->setDescription('Imports stations from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importer->import();
        $output->writeln('<info>Stations imported successfully.</info>');
        return Command::SUCCESS;
    }
}
