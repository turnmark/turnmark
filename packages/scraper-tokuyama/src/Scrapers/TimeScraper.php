<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tokuyama\Scrapers;

use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Factories\HttpBrowserFactory;
use Turnmark\Scraper\Normalizers\Normalizer;
use Turnmark\Scraper\Tokuyama\Contracts\Scraper;

/**
 * @author shimomo
 */
final class TimeScraper implements Scraper
{
    /**
     * @var non-empty-string
     */
    private static string $baseUrl = 'https://www.boatrace-tokuyama.jp';

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
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, 'tenji', $date->format('Ymd'), $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $names = $scraper->filter('.com-rname')->each(fn(Crawler $node): string => $node->text());
        $exhibitionTimes = $scraper->filter('.col10')->each(fn(Crawler $node): string => $node->text());
        $lapTimes = $scraper->filter('.col11')->each(fn(Crawler $node): string => $node->text());
        $turnTimes = $scraper->filter('.col12')->each(fn(Crawler $node): string => $node->text());

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = 18;
        $response['race_number'] = $raceNumber;
        $response['racers'] = [];

        foreach (range(1, 6) as $entryNumber) {
            $name = Normalizer::normalize($names[$entryNumber - 1] ?? null);
            if (!is_string($name) || $name === '') {
                continue;
            }

            $exhibitionTime = Normalizer::normalize($exhibitionTimes[$entryNumber] ?? null);
            if (!is_float($exhibitionTime)) {
                $exhibitionTime = null;
            }

            $lapTime = Normalizer::normalize($lapTimes[$entryNumber + 1] ?? null);
            if (!is_float($lapTime)) {
                $lapTime = null;
            }

            $turnTime = Normalizer::normalize($turnTimes[$entryNumber - 1] ?? null);
            if (!is_float($turnTime)) {
                $turnTime = null;
            }

            $response['racers'][$entryNumber] = [
                'entry_number' => $entryNumber,
                'name' => $name,
                'exhibition_time' => $exhibitionTime,
                'lap_time' => $lapTime,
                'turn_time' => $turnTime,
            ];
        }

        return $response;
    }
}
