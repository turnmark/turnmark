<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Scrapers;

use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Enums\Stadium;
use Turnmark\Scraper\Factories\HttpBrowserFactory;

/**
 * @author shimomo
 */
final class StadiumScraper
{
    /**
     * @var non-empty-string
     */
    private static string $baseUrl = 'https://www.boatrace.jp';

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, mixed>
     */
    public static function scrape(
        DateTimeInterface|string $date,
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/index?hd=%s';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'));
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $stadiums = $scraper
            ->filter('.table1')
            ->eq(0)
            ->filter('table tbody td.is-arrow1.is-fBold.is-fs15')
            ->each(function (Crawler $element): array {
                $stadiumName = $element->filter('a')->filter('img')->attr('alt');
                if ($stadiumName === null || $stadiumName === '') {
                    return [];
                }

                $stadiumName = str_replace('>', '', $stadiumName);
                if ($stadiumName === '') {
                    return [];
                }

                $stadiumNumber = Stadium::fromName($stadiumName)?->value;
                if ($stadiumNumber === null) {
                    return [];
                }

                return [$stadiumNumber => $stadiumName];
            });

        $response = [];

        foreach ($stadiums as $stadium) {
            foreach ($stadium as $number => $name) {
                $response[$number] = $name;
            }
        }

        return $response;
    }
}
