<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadCsvCommand extends Command
{
    protected static $defaultName = 'app:download-csv';

//	private string $csvUrl = 'https://data.gov.lv/dati/lv/dataset/hidrometeorologiskie-noverojumi/resource/c32c7afd-0d05-44fd-8b24-1de85b4bf11d/download/meteorological_stations.csv';
    private string $csvUrl = 'https://data.gov.lv/dati/dataset/40d80be5-0c09-47c4-80f3-fad4bec19f33/resource/c32c7afd-0d05-44fd-8b24-1de85b4bf11d/download/meteo_stacijas.csv';
    protected function configure(): void
    {
        $this->setDescription('Downloads the stations CSV file and saves it locally');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting CSV download...');

        $filePath = __DIR__ . '/../../var/data/stations.csv';

        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $csvContent = @file_get_contents($this->csvUrl);

        if ($csvContent === false) {
            $output->writeln('<error>Failed to download CSV file.</error>');
            return Command::FAILURE;
        }

        file_put_contents($filePath, $csvContent);
        $output->writeln('CSV file saved to: ' . realpath($filePath));

        return Command::SUCCESS;
    }
}
