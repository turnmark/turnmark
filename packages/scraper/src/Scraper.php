<?php

declare(strict_types=1);

namespace Turnmark\Scraper;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Turnmark\Scraper\Converters\Converter;
use Turnmark\Scraper\Scrapers\OddsScraper;
use Turnmark\Scraper\Scrapers\PreviewScraper;
use Turnmark\Scraper\Scrapers\ProgramScraper;
use Turnmark\Scraper\Scrapers\ResultScraper;
use Turnmark\Scraper\Scrapers\StadiumScraper;
use Turnmark\Scraper\Validators\Validator;

/**
 * @author shimomo
 */
final class Scraper
{
    /**
     * @var float
     */
    private const float MIN_CALL_INTERVAL_SECONDS = 3.0;

    /**
     * @var non-empty-list<int<1, 24>>
     */
    private const array STADIUM_NUMBERS = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,
        13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24,
    ];

    /**
     * @var non-empty-list<int<1, 12>>
     */
    private const array RACE_NUMBERS = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,
    ];

    /**
     * @var ?float
     */
    private static ?float $lastThrottleAt = null;

    /**
     * @var bool
     */
    private static bool $showProgress = false;

    /**
     * @return non-empty-list<int<1, 24>>
     */
    public static function getStadiumNumbers(): array
    {
        return self::STADIUM_NUMBERS;
    }

    /**
     * @return non-empty-list<int<1, 12>>
     */
    public static function getRaceNumbers(): array
    {
        return self::RACE_NUMBERS;
    }

    /**
     * @return bool
     */
    public static function getShowProgress(): bool
    {
        return self::$showProgress;
    }

    /**
     * @param bool $showProgress
     * @return void
     */
    public static function setShowProgress(bool $showProgress): void
    {
        self::$showProgress = $showProgress;
    }

    /**
     * @return void
     */
    public static function throttle(): void
    {
        if (self::$lastThrottleAt !== null) {
            $elapsedSeconds = microtime(true) - self::$lastThrottleAt;
            $remainingSeconds = self::MIN_CALL_INTERVAL_SECONDS - $elapsedSeconds;

            if ($remainingSeconds > 0) {
                $sleepMicroseconds = Converter::toIntStrict(
                    $remainingSeconds * 1_000_000.0
                );

                usleep($sleepMicroseconds);
            }
        }

        self::$lastThrottleAt = microtime(true);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, mixed>
     */
    public static function scrapeStadium(
        DateTimeInterface|string $date,
        ?HttpBrowser $httpBrowser = null
    ): array {
        self::throttle();

        return StadiumScraper::scrape($date, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeProgram(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        self::throttle();

        Validator::validateStadiumNumber($stadiumNumber);
        Validator::validateRaceNumber($raceNumber);

        return ProgramScraper::scrape($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapeProgramBulk(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: self::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: self::getRaceNumbers());

        $activeStadiumNumbers = array_keys(self::scrapeStadium($date));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = self::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 出走表のスクレイピングを開始します</info>');
        $progressBar = new ProgressBar($output, $totalSteps);
        $progressBar->setFormat(
            ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
        );
        $progressBar->start();

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    self::scrapeProgram($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $output->writeln('');
        $output->writeln("<info>✅ 出走表のスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapePreview(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        self::throttle();

        Validator::validateStadiumNumber($stadiumNumber);
        Validator::validateRaceNumber($raceNumber);

        return PreviewScraper::scrape($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapePreviewBulk(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: self::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: self::getRaceNumbers());

        $activeStadiumNumbers = array_keys(self::scrapeStadium($date));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = self::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 直前情報のスクレイピングを開始します</info>');
        $progressBar = new ProgressBar($output, $totalSteps);
        $progressBar->setFormat(
            ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
        );
        $progressBar->start();

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    self::scrapePreview($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $output->writeln('');
        $output->writeln("<info>✅ 直前情報のスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeOdds(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        self::throttle();

        Validator::validateStadiumNumber($stadiumNumber);
        Validator::validateRaceNumber($raceNumber);

        return OddsScraper::scrape($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapeOddsBulk(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: self::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: self::getRaceNumbers());

        $activeStadiumNumbers = array_keys(self::scrapeStadium($date));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = self::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 オッズのスクレイピングを開始します</info>');
        $progressBar = new ProgressBar($output, $totalSteps);
        $progressBar->setFormat(
            ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
        );
        $progressBar->start();

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    self::scrapeOdds($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $output->writeln('');
        $output->writeln("<info>✅ オッズのスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeResult(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        self::throttle();

        Validator::validateStadiumNumber($stadiumNumber);
        Validator::validateRaceNumber($raceNumber);

        return ResultScraper::scrape($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapeResultBulk(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: self::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: self::getRaceNumbers());

        $activeStadiumNumbers = array_keys(self::scrapeStadium($date));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = self::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 結果のスクレイピングを開始します</info>');
        $progressBar = new ProgressBar($output, $totalSteps);
        $progressBar->setFormat(
            ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
        );
        $progressBar->start();

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    self::scrapeResult($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $output->writeln('');
        $output->writeln("<info>✅ 結果のスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }
}
