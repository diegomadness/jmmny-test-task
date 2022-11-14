<?php
declare(strict_types=1);

use App\Services\DialogService;
use PHPUnit\Framework\TestCase;

final class DialogTest extends TestCase
{
    public function testCalcMaxMonologueTime(): void
    {
        $dialog = [
            "dialog_start_0" => 0.0,
            "dialog_end_0" => 1.0,
            "dialog_start_1" => 2.0,
            "dialog_end_1" => 5.0
        ];

        $service = new DialogService();
        $this->assertSame(3.0, $service->calcMaxMonologueTime($dialog));
    }

    public function testGetConversationLength(): void
    {
        $dialog1 = [
            "dialog_start_0" => 0.0,
            "dialog_end_0" => 1.0,
            "dialog_start_1" => 2.0,
            "dialog_end_1" => 5.0
        ];

        $dialog2 = [
            "dialog_start_0" => 0.0,
            "dialog_end_0" => 1.0,
            "dialog_start_1" => 2.0,
            "dialog_end_1" => 7.0
        ];

        $service = new DialogService();
        $this->assertSame(7.0, $service->getConversationLength($dialog1, $dialog2));
    }

    public function testCalcTotalDialogLength(): void
    {
        $dialog = [
            "dialog_start_0" => 0.0,
            "dialog_end_0" => 1.0,
            "dialog_start_1" => 2.0,
            "dialog_end_1" => 5.0
        ];

        $service = new DialogService();
        $this->assertSame(4.0, $service->calcTotalDialogLength($dialog));
    }

    public function testCalcDialogPercentage(): void
    {
        $dialog1 = [
            "dialog_start_0" => 0.0,
            "dialog_end_0" => 1.0,
            "dialog_start_1" => 2.0,
            "dialog_end_1" => 10.0
        ];

        $dialog2 = [
            "dialog_start_0" => 1.0,
            "dialog_end_0" => 3.0,
            "dialog_start_1" => 4.0,
            "dialog_end_1" => 8.0
        ];

        $service = new DialogService();
        $this->assertSame(90.0, $service->calcDialogPercentage($dialog1, $service->getConversationLength($dialog1, $dialog2)));
        $this->assertSame(60.0, $service->calcDialogPercentage($dialog2, $service->getConversationLength($dialog1, $dialog2)));
    }
}