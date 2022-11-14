<?php

namespace App\Services;

class SilenceService implements SilenceServiceInterface
{
    /**
     *
     * @param string $path
     * @return array
     */
    public function loadSilenceFromTxt(string $path): array
    {
        $file = fopen($path, "r");
        $clearData = [];
        if ($file) {
            $i = 0;
            while (($line = fgets($file)) !== false) {
                if (strstr($line, "silence_start: ") !== false) {
                    //silence_start branch
                    $line = explode("silence_start: ", $line);
                    $clearData["silence_start_" . $i] = floatval($line[1]);
                } else {
                    //silence_end branch
                    $line = explode("silence_end: ", $line);
                    $line = explode(" |", $line[1]);
                    $clearData["silence_end_" . $i] = floatval($line[0]);
                    $i++;
                }
            }
            fclose($file);
        }
        return $clearData;
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
            "dialog_start_0" => 0,
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