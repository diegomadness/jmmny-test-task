<?php

namespace App\Services;

use App\Repositories\SilenceRepositoryInterface;

/**
 * @property SilenceRepositoryInterface $repository
 */
class SilenceService implements SilenceServiceInterface
{
    public function __construct(SilenceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $path
     * @return array
     */
    public function processSilenceFromTxt(string $path): array
    {
        return $this->processSilence($this->repository->load($path));
    }

    /**
     * @param array $silence
     * @return array
     */
    public function processSilence(array $silence): array
    {
        $clearData = [];
        $i = 0;
        foreach ($silence as $line) {
            if (strstr($line, "silence_start: ") !== false) {
                $clearData["silence_start_" . $i] = $this->processStartString($line);
            } else {
                $clearData["silence_end_" . $i] = $this->processEndString($line);
                $i++;
            }
        }

        return $clearData;
    }

    /**
     * @param string $string
     * @return float
     */
    public function processStartString(string $string): float
    {
        $string = explode("silence_start: ", $string);
        return floatval($string[1]);
    }

    /**
     * @param string $string
     * @return float
     */
    public function processEndString(string $string): float
    {
        $string = explode("silence_end: ", $string);
        $string = explode(" |", $string[1]);
        return floatval($string[0]);
    }

    /**
     *  This function assumes that first line of the dialog starts at 0(because it is not silence).
     *
     * @param array $silenceData
     * @return array
     */
    public function invertSilence(array $silenceData): array
    {
        $dialogData = [
            "dialog_start_0" => 0.0,
            "dialog_end_0" => $silenceData["silence_start_0"]
        ];

        $i = 0;
        while (isset($silenceData["silence_start_" . ($i + 1)])) {
            $dialogData["dialog_start_" . ($i + 1)] = $silenceData["silence_end_" . $i];
            $dialogData["dialog_end_" . ($i + 1)] = $silenceData["silence_start_" . ($i + 1)];
            $i++;
        }
        return $dialogData;
    }

}