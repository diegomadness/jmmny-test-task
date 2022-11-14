<?php

namespace App\Controllers;

use App\Services\DialogServiceInterface;
use App\Services\SilenceServiceInterface;

/**
 * @property DialogServiceInterface $dialogService
 * @property SilenceServiceInterface $silenceService
 */
class ConsoleController
{

    public function __construct(DialogServiceInterface $dialogService, SilenceServiceInterface $silenceService)
    {
        $this->dialogService = $dialogService;
        $this->silenceService = $silenceService;
    }

    public function index() : string
    {
        $userSilence = $this->silenceService->processSilenceFromTxt(getenv("USER_FILE_PATH"));
        $customerSilence = $this->silenceService->processSilenceFromTxt(getenv("CUSTOMER_FILE_PATH"));

        $userDialog = $this->silenceService->invertSilence($userSilence);
        $customerDialog = $this->silenceService->invertSilence($customerSilence);

        $totalLength = $this->dialogService->getConversationLength($userDialog, $customerDialog);


        $result = [
            "longest_user_monologue" => $this->dialogService->calcMaxMonologueTime($userDialog),
            "longest_customer_monologue" => $this->dialogService->calcMaxMonologueTime($customerDialog),
            "user_talk_percentage" => $this->dialogService->calcDialogPercentage($userDialog, $totalLength),
            "user" => $this->dialogService->presentDialog($userDialog),
            "customer" => $this->dialogService->presentDialog($customerDialog)
        ];

        //since there is no view engine used - at least have some line breaks for console output
        $output = print_r(json_encode($result), true);
        $output = str_replace(',"', ',' . "\r\n" . '"', $output);
        return $output;
    }
}