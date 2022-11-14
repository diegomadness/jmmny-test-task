<?php

namespace App\Services;

class DialogService implements DialogServiceInterface
{
    /**
     * @param array $dialogData
     * @return float
     */
    public function calcMaxMonologueTime(array $dialogData): float
    {
        $maxTime = 0;
        for ($i = 0; $i < count($dialogData) / 2; $i++) {
            $maxTime = max($maxTime, $dialogData["dialog_end_" . $i] - $dialogData["dialog_start_" . $i]);
        }

        return round($maxTime, 2);
    }

    /**
     * This function disregards last silence as it is basically unnecessary noise in a dataset
     *
     * It could also get its own ConversationService, but in current scope it feels redundant
     *
     * @param array $dialogData1
     * @param array $dialogData2
     * @return float
     */
    public function getConversationLength(array $dialogData1, array $dialogData2): float
    {
        return max(end($dialogData1), end($dialogData2));
    }

    /**
     * @param array $dialogData
     * @return float
     */
    public function calcTotalDialogLength(array $dialogData): float
    {
        $total = 0;
        for ($i = 0; $i < count($dialogData) / 2; $i++) {
            $total += $dialogData["dialog_end_" . $i] - $dialogData["dialog_start_" . $i];
        }
        return $total;
    }

    /**
     * @param array $dialogData
     * @param float $totalLength
     * @return float
     */
    public function calcDialogPercentage(array $dialogData, float $totalLength): float
    {
        $dialogLength = $this->calcTotalDialogLength($dialogData);
        return round($dialogLength / $totalLength * 100, 1);
    }

    /**
     * This one could be a part of some Presenter that is used on the frontend part
     *
     * @param array $dialogData
     * @return array
     */
    public function presentDialog(array $dialogData): array
    {
        $presented = [];
        for ($i = 0; $i < count($dialogData) / 2; $i++) {
            $presented[] = [$dialogData["dialog_start_" . $i], $dialogData["dialog_end_" . $i]];
        }
        return $presented;
    }
}
