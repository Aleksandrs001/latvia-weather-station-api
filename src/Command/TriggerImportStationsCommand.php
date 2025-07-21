<?php

namespace App\Command;

use App\Message\ImportStationsMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:download-csv-auto',
    description: 'Dispatch a message to import stations from CSV file',
)]
class TriggerImportStationsCommand extends Command
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        new(DownloadCsvCommand::class)();

        $this->bus->dispatch(new ImportStationsMessage());

        $output->writeln('<info>Message dispatched!</info>');

        return Command::SUCCESS;
    }
}
