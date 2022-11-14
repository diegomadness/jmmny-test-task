<?php

namespace App\Services;

interface DialogServiceInterface
{
    /**
     * @param array $dialogData
     * @return float
     */
    public function calcMaxMonologueTime(array $dialogData): float;

    /**
     * @param array $dialogData1
     * @param array $dialogData2
     * @return float
     */
    public function getConversationLength(array $dialogData1, array $dialogData2): float;

    /**
     * @param array $dialogData
     * @return float
     */
    public function calcTotalDialogLength(array $dialogData): float;

    /**
     * @param array $dialogData
     * @param float $totalLength
     * @return float
     */
    public function calcDialogPercentage(array $dialogData, float $totalLength): float;

    /**
     * @param array $dialogData
     * @return array
     */
    public function presentDialog(array $dialogData): array;
}
