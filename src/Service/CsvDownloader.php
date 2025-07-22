<?php

namespace App\Service;

class CsvDownloader
{
    private string $csvPath;
    private string $checksumPath;

    public function __construct(string $csvPath)
    {
        $this->csvPath = $csvPath;
        $this->checksumPath = $csvPath . '.checksum';
    }

    public function download(): bool
    {
        $url = 'https://data.gov.lv/dati/dataset/40d80be5-0c09-47c4-80f3-fad4bec19f33/resource/c32c7afd-0d05-44fd-8b24-1de85b4bf11d/download/meteo_stacijas.csv'; // ссылка на CSV

        $tempFile = tempnam(sys_get_temp_dir(), 'csv_');
        file_put_contents($tempFile, file_get_contents($url));

        $newChecksum = md5_file($tempFile);

        $oldChecksum = file_exists($this->checksumPath) ? file_get_contents($this->checksumPath) : null;

        if ($newChecksum === $oldChecksum) {
            unlink($tempFile);
            return false;
        }

        rename($tempFile, $this->csvPath);
        file_put_contents($this->checksumPath, $newChecksum);

        return true;
    }

}
