<?php

namespace App\Repositories\Txt;

use App\Repositories\SilenceRepositoryInterface;

class SilenceRepository implements SilenceRepositoryInterface
{
    /**
     * @param string $path
     * @return array
     */
    public function load(string $path): array
    {
        $file = fopen($path, "r");
        $fileData = [];
        if ($file) {
            while (($line = fgets($file)) !== false) {
                $fileData[] = $line;
            }
            fclose($file);
        }
        return $fileData;
    }
}