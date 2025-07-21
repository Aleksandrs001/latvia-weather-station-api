<?php

namespace App\MessageHandler;

use App\Command\DownloadCsvCommand;
use App\Message\ImportStationsMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportStationsMessageHandler
{
    public function __invoke(ImportStationsMessage $message)
    {
        $this->importStations();
    }

    private function importStations(): void
    {
        new(DownloadCsvCommand::class)();
    }
}


