<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Scrapers;

use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Converters\Converter;
use Turnmark\Scraper\Enums\Stadium;
use Turnmark\Scraper\Factories\HttpBrowserFactory;

/**
 * Reads which stadiums hold a race on a date. Every batch method starts here, so a name that
 * cannot be recognised is dropped on its own rather than taking the whole list down.
 *
 * @author shimomo
 */
final class StadiumScraper
{
    /**
     * @var non-empty-string
     */
    private const string BASE_URL = 'https://www.boatrace.jp';

    /**
     * The stadium is named nowhere as text: it is the alt of the banner image, which carries a
     * marker alongside the name.
     *
     * @param \DateTimeInterface|non-empty-string $date
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, non-empty-string>
     */
    public static function scrape(
        DateTimeInterface|string $date,
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/index?hd=%s';
        $scraperUrl = sprintf($scraperFormat, self::BASE_URL, $date->format('Ymd'));
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

                // A name the enum does not know must not take the whole list down with it: every
                // batch method reads this list first, so an unexpected alt would stop everything.
                $stadiumNumber = Converter::toEnumOrNull(
                    fn() => Stadium::fromName($stadiumName)
                )?->value;

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
