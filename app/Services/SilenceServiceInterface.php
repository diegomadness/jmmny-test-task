<?php

namespace App\Services;

interface SilenceServiceInterface
{
    /**
     * @param string $path
     * @return array
     */
    public function processSilenceFromTxt(string $path): array;


    /**
     * @param array $silenceData
     * @return array
     */
    public function invertSilence(array $silenceData): array;

}