<?php

namespace App\Command;

use App\Service\CsvDownloader;
use App\Service\StationImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-csv',
    description: 'import CSV file'
)]
class ImportStationsCommand extends Command
{
    protected static $defaultName = 'app:import-stations';

    public function __construct(
        private readonly StationImporter $importer,
        private readonly CsvDownloader   $csvDownloader
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Imports stations from CSV file');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Checking for CSV updates...</info>');

        $updated = $this->csvDownloader->download();

        if (!$updated) {
            $output->writeln('<comment>No changes detected, skipping import.</comment>');
            return Command::SUCCESS;
        }

        $output->writeln('<info>CSV updated, starting import...</info>');
        $this->importer->import();

        $output->writeln('<info>Stations imported successfully.</info>');
        return Command::SUCCESS;
    }

}
