<?php
declare(strict_types=1);

use App\Repositories\Txt\SilenceRepository;
use App\Services\SilenceService;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class SilenceTest extends TestCase
{
    /**
     * @var SilenceService|Stub
     */
    private Stub $repository;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->repository = $this->createStub(SilenceRepository::class);
        $this->repository->method('load')
            ->willReturn([
                "[silencedetect @ 0x7fa7edd0c160] silence_start: 1",
                "[silencedetect @ 0x7fa7edd0c160] silence_end: 2 | silence_duration: 1",
                "[silencedetect @ 0x7fa7edd0c160] silence_start: 3",
                "[silencedetect @ 0x7fa7edd0c160] silence_end: 4 | silence_duration: 1",
            ]);
        parent::__construct($name, $data, $dataName);
    }

    public function testSilenceProcessing(): void
    {
        $expected = [
            "silence_start_0" => 1.0,
            "silence_end_0" => 2.0,
            "silence_start_1" => 3.0,
            "silence_end_1" => 4.0
        ];

        $service = new SilenceService($this->repository);
        $this->assertSame($expected, $service->processSilenceFromTxt('qwe'));
    }

    public function testInvertSilence(): void
    {
        $service = new SilenceService($this->repository);
        $silence = [
            "silence_start_0" => 1.0,
            "silence_end_0" => 2.0,
            "silence_start_1" => 3.0,
            "silence_end_1" => 4.0
        ];
        $expected = [
            "dialog_start_0" => 0.0,
            "dialog_end_0" => 1.0,
            "dialog_start_1" => 2.0,
            "dialog_end_1" => 3.0
        ];
        $this->assertSame($expected, $service->invertSilence($silence));
    }
}