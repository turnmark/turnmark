<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Mikuni\Contracts;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;

/**
 * @author shimomo
 */
interface Scraper
{
    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrape(
        DateTimeInterface|string $date,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array;
}
