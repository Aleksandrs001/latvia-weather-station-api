<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

#[AsCommand(
    name: 'app:update-stations',
    description: 'Download and Import the station information'
)]
class UpdateStationsCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Downloads the stations CSV file and saves it locally');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting station update...');

        $downloadCommand = $this->getApplication()->find('app:download-csv');
        $downloadResult = $downloadCommand->run(new ArrayInput([]), new ConsoleOutput());

        if ($downloadResult !== Command::SUCCESS) {
            $output->writeln('<error>Failed to download CSV file.</error>');
            return Command::FAILURE;
        }

        $importCommand = $this->getApplication()->find('app:import-csv');
        $importResult = $importCommand->run(new ArrayInput([]), new ConsoleOutput());

        if ($importResult !== Command::SUCCESS) {
            $output->writeln('<error>Failed to import station data.</error>');
            return Command::FAILURE;
        }

        $output->writeln('Station Download and Import successfully.');
        return Command::SUCCESS;
    }
}
