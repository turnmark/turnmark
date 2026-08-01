<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Fukuoka;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Turnmark\Scraper\Fukuoka\Scrapers\TimeScraper;
use Turnmark\Scraper\Scraper as BoatraceScraper;
use Turnmark\Scraper\Validators\Validator;

/**
 * @author shimomo
 */
final class Scraper
{
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
        BoatraceScraper::throttle();

        Validator::validateRaceNumber($raceNumber);

        return TimeScraper::scrape($date, $raceNumber, $httpBrowser);
    }
}
