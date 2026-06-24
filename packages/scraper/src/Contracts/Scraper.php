<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Contracts;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;

/**
 * @author shimomo
 */
interface Scraper
{
    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrape(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array;
}
