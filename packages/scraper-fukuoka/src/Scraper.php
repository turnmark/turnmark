<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Fukuoka;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Turnmark\Scraper\Converters\Converter;
use Turnmark\Scraper\Fukuoka\Scrapers\TimeScraper;
use Turnmark\Scraper\Validators\Validator;

/**
 * @author shimomo
 */
final class Scraper
{
    /**
     * @var ?float
     */
    private static ?float $lastThrottleAt = null;

    /**
     * @var float
     */
    private const float MIN_CALL_INTERVAL_SECONDS = 3.0;

    /**
     * @var non-empty-list<int<1, 12>>
     */
    private const array RACE_NUMBERS = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,
    ];

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
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeTime(
        DateTimeInterface|string $date,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        self::throttle();

        Validator::validateRaceNumber($raceNumber);

        return TimeScraper::scrape($date, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 12>, array<non-empty-string, mixed>>
     */
    public static function scrapeTimeBulk(
        DateTimeInterface|string $date,
        array $raceNumbers = self::RACE_NUMBERS,
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        foreach (array_unique($raceNumbers) as $raceNumber) {
            $response[$raceNumber] =
                self::scrapeTime($date, $raceNumber, $httpBrowser);
        }

        return $response;
    }
}
