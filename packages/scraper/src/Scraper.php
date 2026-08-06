<?php

declare(strict_types=1);

namespace Turnmark\Scraper;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Turnmark\Scraper\Converters\Converter;
use Turnmark\Scraper\Scrapers\OddsScraper;
use Turnmark\Scraper\Scrapers\PreviewScraper;
use Turnmark\Scraper\Scrapers\ProgramScraper;
use Turnmark\Scraper\Scrapers\ResultScraper;
use Turnmark\Scraper\Scrapers\StadiumScraper;
use Turnmark\Scraper\Validators\Validator;
use ValueError;

/**
 * The entry point of the package. Every method here spaces its request away from the
 * previous one and checks its arguments before handing the work to the scraper behind it,
 * neither of which the scrapers do on their own.
 *
 * @author shimomo
 */
final class Scraper
{
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
     * @var float
     */
    private const float DEFAULT_MIN_CALL_INTERVAL_SECONDS = 3.0;

    /**
     * @var float
     */
    private static float $minCallIntervalSeconds = self::DEFAULT_MIN_CALL_INTERVAL_SECONDS;

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
     * @return float
     */
    public static function getMinCallIntervalSeconds(): float
    {
        return self::$minCallIntervalSeconds;
    }

    /**
     * The interval can be widened but not taken below a second, so that no setting turns the
     * package into something that hammers the site.
     *
     * @param float $seconds
     * @return void
     * @throws \ValueError
     */
    public static function setMinCallIntervalSeconds(float $seconds): void
    {
        if ($seconds < 1.0) {
            throw new ValueError(
                sprintf('$seconds must be 1 or greater, %s given.', $seconds)
            );
        }

        self::$minCallIntervalSeconds = $seconds;
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
     * Holds the next request back until enough time has passed since the last one. The clock is
     * shared by every scraper in every package, so a batch run keeps to one pace no matter how
     * many entry points it goes through.
     *
     * @return void
     */
    public static function throttle(): void
    {
        if (self::$lastThrottleAt !== null) {
            $elapsedSeconds = microtime(true) - self::$lastThrottleAt;
            $remainingSeconds = self::$minCallIntervalSeconds - $elapsedSeconds;

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
     * @return array<int<1, 24>, non-empty-string>
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
}
