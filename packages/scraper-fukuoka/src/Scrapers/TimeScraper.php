<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Fukuoka\Scrapers;

use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Contracts\LocalScraper;
use Turnmark\Scraper\Factories\HttpBrowserFactory;
use Turnmark\Scraper\Normalizers\Normalizer;

/**
 * Reads the exhibition times Fukuoka publishes on its own site, which carries more of the run
 * than the official page does: the lap, turn and straight times as well. The stadium is fixed at 22.
 *
 * @author shimomo
 */
final class TimeScraper implements LocalScraper
{
    /**
     * @var non-empty-string
     */
    private const string BASE_URL = 'https://www.boatrace-fukuoka.com';

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    #[\Override]
    public static function scrape(
        DateTimeInterface|string $date,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/modules/yosou/%s.php?day=%s&race=%d';
        $scraperUrl = sprintf($scraperFormat, self::BASE_URL, 'tenji_info', $date->format('Ymd'), $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $names = $scraper->filter('.com-rname')->each(fn(Crawler $node): string => $node->text());
        $exhibitionTimes = $scraper->filter('.col6')->each(fn(Crawler $node): string => $node->text());
        $lapTimes = $scraper->filter('.col7')->each(fn(Crawler $node): string => $node->text());
        $turnTimes = $scraper->filter('.col8')->each(fn(Crawler $node): string => $node->text());
        $straightTimes = $scraper->filter('.col9')->each(fn(Crawler $node): string => $node->text());

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = 22;
        $response['race_number'] = $raceNumber;
        $response['racers'] = [];

        // A data cell shares its class with the header cells standing above it in the column,
        // and the number of those differs from column to column, so each list is read from its
        // own offset.
        foreach (range(1, 6) as $entryNumber) {
            $name = Normalizer::normalize($names[$entryNumber - 1] ?? null);
            if (!is_string($name) || $name === '') {
                continue;
            }

            $exhibitionTime = Normalizer::normalize($exhibitionTimes[$entryNumber] ?? null);
            if (!is_float($exhibitionTime)) {
                $exhibitionTime = null;
            }

            $lapTime = Normalizer::normalize($lapTimes[$entryNumber] ?? null);
            if (!is_float($lapTime)) {
                $lapTime = null;
            }

            $turnTime = Normalizer::normalize($turnTimes[$entryNumber] ?? null);
            if (!is_float($turnTime)) {
                $turnTime = null;
            }

            $straightTime = Normalizer::normalize($straightTimes[$entryNumber] ?? null);
            if (!is_float($straightTime)) {
                $straightTime = null;
            }

            $response['racers'][$entryNumber] = [
                'entry_number' => $entryNumber,
                'name' => $name,
                'exhibition_time' => $exhibitionTime,
                'lap_time' => $lapTime,
                'turn_time' => $turnTime,
                'straight_time' => $straightTime,
            ];
        }

        return $response;
    }
}
