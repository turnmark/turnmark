<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Scrapers;

use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Contracts\Scraper;
use Turnmark\Scraper\Factories\HttpBrowserFactory;
use Turnmark\Scraper\Filters\Filter;
use Turnmark\Scraper\Filters\WindDirectionFilter;
use Turnmark\Scraper\Parsers\Parser;
use Turnmark\Scraper\Parsers\PreviewParser;

/**
 * @author shimomo
 */
final class PreviewScraper implements Scraper
{
    /**
     * @var non-empty-string
     */
    private static string $baseUrl = 'https://www.boatrace.jp';

    /**
     * @var non-empty-string
     */
    private static string $baseXPath = 'descendant-or-self::body/main/div/div/div';

    /**
     * @var int<0, 1>
     */
    private static int $baseLevel = 0;

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    #[\Override]
    public static function scrape(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/beforeinfo?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $windSpeedFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[3]/div/span[2]';
        $windSpeedXPath = sprintf($windSpeedFormat, self::$baseXPath, self::$baseLevel + 5);
        $windSpeedSource = Filter::byXPath($scraper, $windSpeedXPath);
        $windSpeed = PreviewParser::parseWindSpeed($windSpeedSource);

        $windDirectionFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[4]/p';
        $windDirectionXPath = sprintf($windDirectionFormat, self::$baseXPath, self::$baseLevel + 5);
        $windDirectionSource = WindDirectionFilter::byXPath($scraper, $windDirectionXPath);
        $windDirection = PreviewParser::parseWindDirection($windDirectionSource);

        $waveHeightFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[6]/div/span[2]';
        $waveHeightXPath = sprintf($waveHeightFormat, self::$baseXPath, self::$baseLevel + 5);
        $waveHeightSource = Filter::byXPath($scraper, $waveHeightXPath);
        $waveHeight = PreviewParser::parseWaveHeight($waveHeightSource);

        $weatherFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[2]/div/span';
        $weatherXPath = sprintf($weatherFormat, self::$baseXPath, self::$baseLevel + 5);
        $weatherSource = Filter::byXPath($scraper, $weatherXPath);
        $weather = PreviewParser::parseWeather($weatherSource);

        $airTemperatureFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[1]/div/span[2]';
        $airTemperatureXPath = sprintf($airTemperatureFormat, self::$baseXPath, self::$baseLevel + 5);
        $airTemperatureSource = Filter::byXPath($scraper, $airTemperatureXPath);
        $airTemperature = PreviewParser::parseAirTemperature($airTemperatureSource);

        $waterTemperatureFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[5]/div/span[2]';
        $waterTemperatureXPath = sprintf($waterTemperatureFormat, self::$baseXPath, self::$baseLevel + 5);
        $waterTemperatureSource = Filter::byXPath($scraper, $waterTemperatureXPath);
        $waterTemperature = PreviewParser::parseWaterTemperature($waterTemperatureSource);

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = $stadiumNumber;
        $response['race_number'] = $raceNumber;

        $response += $windSpeed;
        $response += $windDirection;
        $response += $waveHeight;
        $response += $weather;
        $response += $airTemperature;
        $response += $waterTemperature;

        $response += self::scrapeRacers($scraper);

        return $response;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @return array<non-empty-string, mixed>
     */
    private static function scrapeRacers(Crawler $scraper): array
    {
        $response = ['racers' => []];

        foreach (range(1, 6) as $index) {
            $entryNumberFormat = '%s/div[2]/div[%d]/div[2]/div[1]/table/tbody/tr[%s]/td/div/span[1]';
            $entryNumberXPath = sprintf($entryNumberFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $entryNumberSource = Filter::byXPath($scraper, $entryNumberXPath);
            $entryNumber = Parser::parseEntryNumber($entryNumberSource);

            $course = ['course_number' => $index];

            $startTimingFormat = '%s/div[2]/div[%d]/div[2]/div[1]/table/tbody/tr[%s]/td/div/span[3]';
            $startTimingXPath = sprintf($startTimingFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $startTimingSource = Filter::byXPath($scraper, $startTimingXPath);
            $startTiming = PreviewParser::parseStartTiming($startTimingSource);

            if (!isset($entryNumber['entry_number'])) {
                $entryNumber['entry_number'] = $index;
                $course['course_number'] = null;
            }

            $entryNumberKey = $entryNumber['entry_number'];

            if (!in_array($entryNumberKey, range(1, 6), true)) {
                continue;
            }

            $response['racers'][$entryNumberKey] ??= [];
            $response['racers'][$entryNumberKey] += $entryNumber;
            $response['racers'][$entryNumberKey] += $course;
            $response['racers'][$entryNumberKey] += $startTiming;
        }

        foreach (range(1, 6) as $index) {
            $entryNumberFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[1]';
            $entryNumberXPath = sprintf($entryNumberFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $entryNumberSource = Filter::byXPath($scraper, $entryNumberXPath);
            $entryNumber = Parser::parseEntryNumber($entryNumberSource);

            $weightFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[4]';
            $weightXPath = sprintf($weightFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $weightSource = Filter::byXPath($scraper, $weightXPath);
            $weight = PreviewParser::parseWeight($weightSource);

            $weightAdjustmentFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[3]/td[1]';
            $weightAdjustmentXPath = sprintf($weightAdjustmentFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $weightAdjustmentSource = Filter::byXPath($scraper, $weightAdjustmentXPath);
            $weightAdjustment = PreviewParser::parseWeightAdjustment($weightAdjustmentSource);

            $exhibitionTimeFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[5]';
            $exhibitionTimeXPath = sprintf($exhibitionTimeFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $exhibitionTimeSource = Filter::byXPath($scraper, $exhibitionTimeXPath);
            $exhibitionTime = PreviewParser::parseExhibitionTime($exhibitionTimeSource);

            $tiltAdjustmentFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[6]';
            $tiltAdjustmentXPath = sprintf($tiltAdjustmentFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $tiltAdjustmentSource = Filter::byXPath($scraper, $tiltAdjustmentXPath);
            $tiltAdjustment = PreviewParser::parseTiltAdjustment($tiltAdjustmentSource);

            if (!isset($entryNumber['entry_number'])) {
                $entryNumber['entry_number'] = $index;
            }

            $entryNumberKey = $entryNumber['entry_number'];

            if (!in_array($entryNumberKey, range(1, 6), true)) {
                continue;
            }

            $response['racers'][$entryNumberKey] ??= [];
            $response['racers'][$entryNumberKey] += $entryNumber;
            $response['racers'][$entryNumberKey] += $weight;
            $response['racers'][$entryNumberKey] += $weightAdjustment;
            $response['racers'][$entryNumberKey] += $exhibitionTime;
            $response['racers'][$entryNumberKey] += $tiltAdjustment;
        }

        ksort($response['racers'], SORT_NUMERIC);

        return $response;
    }
}
