<?php
//todo: check strict types, property types, return types, etc
//todo: create composer file with platform requirements
//todo: make autoloading
//todo: write Unit tests /w test doubles
//todo: refactor stuff into classes to make IoC friendly code
//todo: connect phpcs and check storm settings for PSR compliance
//todo: use .env file for file names

$userSilence = loadSilenceFromTxt("user-channel.txt");
$customerSilence = loadSilenceFromTxt("customer-channel.txt");

$userDialog = invertSilence($userSilence);
$customerDialog = invertSilence($customerSilence);

$totalLength = getConversationLength($userDialog, $customerDialog);

$result = [
    "longest_user_monologue" => calcMaxMonologueTime($userDialog),
    "longest_customer_monologue" => calcMaxMonologueTime($customerDialog),
    "user_talk_percentage" => calcDialogPercentage($userDialog, $totalLength),
    "user" => [presentDialog($userDialog)],
    "customer" => [presentDialog($customerDialog)]
];

//some line breaks for console output
$output = print_r(json_encode($result), true);
$output = str_replace(',"',','."\r\n".'"',$output);
echo $output;


/**
{
"longest_user_monologue": 416.18,
"longest_customer_monologue": 1152.82,
"user_talk_percentage": 41.92,
"user":[
[0,3.504],[6.656,14],[19.712,20.144],[27.264,36.528],[41.728,47.28],[49.792,61.104],[65.024,79.024],
[ ... and many more ...]
],
"customer":[
[0,1.84],[4.48,26.928],[29.184,29.36],[31.744,56.624],[58.624,66.992],[69.632,91.184],
[ ... and many more ...]
]
}
 */

/**
 *
 * @param string $path
 * @return array
 */
function loadSilenceFromTxt(string $path): array
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
function invertSilence(array $silenceData): array
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

/**
 * @param array $dialogData
 * @return float
 */
function calcMaxMonologueTime(array $dialogData): float
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
 * @param array $dialogData1
 * @param array $dialogData2
 * @return float
 */
function getConversationLength(array $dialogData1, array $dialogData2): float
{
    return max(end($dialogData1), end($dialogData2));
}


/**
 * @param array $dialogData
 * @return float
 */
function calcTotalDialogLength(array $dialogData): float
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
function calcDialogPercentage(array $dialogData, float $totalLength): float
{
    $dialogLength = calcTotalDialogLength($dialogData);
    return round($dialogLength / $totalLength * 100, 1);
}

/**
 * @param array $dialogData
 * @return array
 */
function presentDialog(array $dialogData): array
{
    $presented = [];
    for ($i = 0; $i < count($dialogData) / 2; $i++) {
        $presented[] =  [$dialogData["dialog_start_" . $i], $dialogData["dialog_end_" . $i]];
    }
    return $presented;
}
