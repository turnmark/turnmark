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
    private const string BASE_URL = 'https://www.boatrace.jp';

    /**
     * @var non-empty-string
     */
    private const string BASE_XPATH = 'descendant-or-self::body/main/div/div/div';

    /**
     * @var non-empty-list<non-empty-string>
     */
    private const array RACER_KEYS = [
        'entry_number',
        'course_number',
        'start_timing_source',
        'start_timing',
        'weight_source',
        'weight',
        'weight_adjustment_source',
        'weight_adjustment',
        'exhibition_time_source',
        'exhibition_time',
        'tilt_adjustment_source',
        'tilt_adjustment',
        'propeller',
        'parts',
    ];

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
        $scraperUrl = sprintf($scraperFormat, self::BASE_URL, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::BASE_XPATH);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $windSpeedFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[3]/div/span[2]';
        $windSpeedXPath = sprintf($windSpeedFormat, self::BASE_XPATH, self::$baseLevel + 5);
        $windSpeedSource = Filter::byXPath($scraper, $windSpeedXPath);
        $windSpeed = PreviewParser::parseWindSpeed($windSpeedSource);

        $windDirectionFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[4]/p';
        $windDirectionXPath = sprintf($windDirectionFormat, self::BASE_XPATH, self::$baseLevel + 5);
        $windDirectionSource = WindDirectionFilter::byXPath($scraper, $windDirectionXPath);
        $windDirection = PreviewParser::parseWindDirection($windDirectionSource);

        $waveHeightFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[6]/div/span[2]';
        $waveHeightXPath = sprintf($waveHeightFormat, self::BASE_XPATH, self::$baseLevel + 5);
        $waveHeightSource = Filter::byXPath($scraper, $waveHeightXPath);
        $waveHeight = PreviewParser::parseWaveHeight($waveHeightSource);

        $weatherFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[2]/div/span';
        $weatherXPath = sprintf($weatherFormat, self::BASE_XPATH, self::$baseLevel + 5);
        $weatherSource = Filter::byXPath($scraper, $weatherXPath);
        $weather = PreviewParser::parseWeather($weatherSource);

        $airTemperatureFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[1]/div/span[2]';
        $airTemperatureXPath = sprintf($airTemperatureFormat, self::BASE_XPATH, self::$baseLevel + 5);
        $airTemperatureSource = Filter::byXPath($scraper, $airTemperatureXPath);
        $airTemperature = PreviewParser::parseAirTemperature($airTemperatureSource);

        $waterTemperatureFormat = '%s/div[2]/div[%d]/div[2]/div[2]/div[1]/div[5]/div/span[2]';
        $waterTemperatureXPath = sprintf($waterTemperatureFormat, self::BASE_XPATH, self::$baseLevel + 5);
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
        $racers = self::scrapePreviewTable($scraper);

        $template = array_fill_keys(self::RACER_KEYS, null);

        $response = ['racers' => []];

        foreach (range(1, 6) as $entryNumberKey) {
            $response['racers'][$entryNumberKey] = array_replace($template, [
                'entry_number' => $entryNumberKey,
            ], $racers[$entryNumberKey] ?? []);
        }

        return $response;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @return array<int<1, 6>, array<non-empty-string, mixed>>
     */
    private static function scrapePreviewTable(Crawler $scraper): array
    {
        $response = [];

        foreach (range(1, 6) as $index) {
            $entryNumberFormat = '%s/div[2]/div[%d]/div[2]/div[1]/table/tbody/tr[%s]/td/div/span[1]';
            $entryNumberXPath = sprintf($entryNumberFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $entryNumberSource = Filter::byXPath($scraper, $entryNumberXPath);
            $entryNumber = Parser::parseEntryNumber($entryNumberSource);

            $course = ['course_number' => $index];

            $startTimingFormat = '%s/div[2]/div[%d]/div[2]/div[1]/table/tbody/tr[%s]/td/div/span[3]';
            $startTimingXPath = sprintf($startTimingFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
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

            $response[$entryNumberKey] ??= [];
            $response[$entryNumberKey] += $entryNumber;
            $response[$entryNumberKey] += $course;
            $response[$entryNumberKey] += $startTiming;
        }

        foreach (range(1, 6) as $index) {
            $entryNumberFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[1]';
            $entryNumberXPath = sprintf($entryNumberFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $entryNumberSource = Filter::byXPath($scraper, $entryNumberXPath);
            $entryNumber = Parser::parseEntryNumber($entryNumberSource);

            $weightFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[4]';
            $weightXPath = sprintf($weightFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $weightSource = Filter::byXPath($scraper, $weightXPath);
            $weight = PreviewParser::parseWeight($weightSource);

            $weightAdjustmentFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[3]/td[1]';
            $weightAdjustmentXPath = sprintf($weightAdjustmentFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $weightAdjustmentSource = Filter::byXPath($scraper, $weightAdjustmentXPath);
            $weightAdjustment = PreviewParser::parseWeightAdjustment($weightAdjustmentSource);

            $exhibitionTimeFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[5]';
            $exhibitionTimeXPath = sprintf($exhibitionTimeFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $exhibitionTimeSource = Filter::byXPath($scraper, $exhibitionTimeXPath);
            $exhibitionTime = PreviewParser::parseExhibitionTime($exhibitionTimeSource);

            $tiltAdjustmentFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[6]';
            $tiltAdjustmentXPath = sprintf($tiltAdjustmentFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $tiltAdjustmentSource = Filter::byXPath($scraper, $tiltAdjustmentXPath);
            $tiltAdjustment = PreviewParser::parseTiltAdjustment($tiltAdjustmentSource);

            $propellerFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[7]';
            $propellerXPath = sprintf($propellerFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $propellerSource = Filter::byXPath($scraper, $propellerXPath);
            $propeller = PreviewParser::parsePropeller($propellerSource);

            // Look for the cell itself before counting the li, so that a missing cell stays
            // apart from a cell holding no exchange.
            $partsFormat = '%s/div[2]/div[%d]/div[1]/div[1]/table/tbody[%s]/tr[1]/td[8]';
            $partsXPath = sprintf($partsFormat, self::BASE_XPATH, self::$baseLevel + 5, $index);
            $partsSource = Filter::byXPath($scraper, $partsXPath) === null
                ? null
                : Filter::byXPathAsList($scraper, $partsXPath . '/ul/li');
            $parts = PreviewParser::parseParts($partsSource);

            if (!isset($entryNumber['entry_number'])) {
                $entryNumber['entry_number'] = $index;
            }

            $entryNumberKey = $entryNumber['entry_number'];

            if (!in_array($entryNumberKey, range(1, 6), true)) {
                continue;
            }

            $response[$entryNumberKey] ??= [];
            $response[$entryNumberKey] += $entryNumber;
            $response[$entryNumberKey] += $weight;
            $response[$entryNumberKey] += $weightAdjustment;
            $response[$entryNumberKey] += $exhibitionTime;
            $response[$entryNumberKey] += $tiltAdjustment;
            $response[$entryNumberKey] += $propeller;
            $response[$entryNumberKey] += $parts;
        }

        return $response;
    }
}
