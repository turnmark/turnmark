<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Scrapers;

use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Contracts\Scraper;
use Turnmark\Scraper\Converters\Converter;
use Turnmark\Scraper\Factories\HttpBrowserFactory;
use Turnmark\Scraper\Filters\Filter;
use Turnmark\Scraper\Filters\WindDirectionFilter;
use Turnmark\Scraper\Parsers\Parser;
use Turnmark\Scraper\Parsers\ResultParser;

/**
 * @author shimomo
 */
final class ResultScraper implements Scraper
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

        $scraperFormat = '%s/owpc/pc/race/raceresult?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $windSpeedFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[3]/div/span[2]';
        $windSpeedXPath = sprintf($windSpeedFormat, self::$baseXPath, self::$baseLevel + 6);
        $windSpeedSource = Filter::byXPath($scraper, $windSpeedXPath);
        $windSpeed = ResultParser::parseWindSpeed($windSpeedSource);

        $windDirectionFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[4]/p';
        $windDirectionXPath = sprintf($windDirectionFormat, self::$baseXPath, self::$baseLevel + 6);
        $windDirectionSource = WindDirectionFilter::byXPath($scraper, $windDirectionXPath);
        $windDirection = ResultParser::parseWindDirection($windDirectionSource);

        $waveHeightFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[6]/div/span[2]';
        $waveHeightXPath = sprintf($waveHeightFormat, self::$baseXPath, self::$baseLevel + 6);
        $waveHeightSource = Filter::byXPath($scraper, $waveHeightXPath);
        $waveHeight = ResultParser::parseWaveHeight($waveHeightSource);

        $weatherFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[2]/div/span';
        $weatherXPath = sprintf($weatherFormat, self::$baseXPath, self::$baseLevel + 6);
        $weatherSource = Filter::byXPath($scraper, $weatherXPath);
        $weather = ResultParser::parseWeather($weatherSource);

        $airTemperatureFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[1]/div/span[2]';
        $airTemperatureXPath = sprintf($airTemperatureFormat, self::$baseXPath, self::$baseLevel + 6);
        $airTemperatureSource = Filter::byXPath($scraper, $airTemperatureXPath);
        $airTemperature = ResultParser::parseAirTemperature($airTemperatureSource);

        $waterTemperatureFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[5]/div/span[2]';
        $waterTemperatureXPath = sprintf($waterTemperatureFormat, self::$baseXPath, self::$baseLevel + 6);
        $waterTemperatureSource = Filter::byXPath($scraper, $waterTemperatureXPath);
        $waterTemperature = ResultParser::parseWaterTemperature($waterTemperatureSource);

        $techniqueFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[2]/div[2]/table/tbody/tr/td';
        $techniqueXPath = sprintf($techniqueFormat, self::$baseXPath, self::$baseLevel + 6);
        $techniqueSource = Filter::byXPath($scraper, $techniqueXPath);
        $technique = ResultParser::parseTechnique($techniqueSource);

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
        $response += $technique;

        $response += self::scrapeRacers($scraper);
        $response += self::scrapePayouts($scraper);

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
            $entryNumberFormat = '%s/div[2]/div[%s]/div[2]/div/table/tbody/tr[%s]/td/div/span[1]';
            $entryNumberXPath = sprintf($entryNumberFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $entryNumberSource = Filter::byXPath($scraper, $entryNumberXPath);
            $entryNumber = Parser::parseEntryNumber($entryNumberSource);

            $course = ['course_number' => $index];

            $startTimingFormat = '%s/div[2]/div[%s]/div[2]/div/table/tbody/tr[%s]/td/div/span[3]/span';
            $startTimingXPath = sprintf($startTimingFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $startTimingSource = Filter::byXPath($scraper, $startTimingXPath);
            $startTiming = ResultParser::parseStartTiming($startTimingSource);

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
            $placeFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[1]';
            $placeXPath = sprintf($placeFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $placeSource = Filter::byXPath($scraper, $placeXPath);
            $place = ResultParser::parsePlace($placeSource);

            $entryNumberFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[2]';
            $entryNumberXPath = sprintf($entryNumberFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $entryNumberSource = Filter::byXPath($scraper, $entryNumberXPath);
            $entryNumber = Parser::parseEntryNumber($entryNumberSource);

            $numberFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[3]/span[1]';
            $numberXPath = sprintf($numberFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $numberSource = Filter::byXPath($scraper, $numberXPath);
            $number = Parser::parseNumber($numberSource);

            $nameFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[3]/span[2]';
            $nameXPath = sprintf($nameFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $nameSource = Filter::byXPath($scraper, $nameXPath);
            $name = Parser::parseName($nameSource);

            $entryNumberKey = $entryNumber['entry_number'];

            if (!in_array($entryNumberKey, range(1, 6), true)) {
                continue;
            }

            $response['racers'][$entryNumberKey] ??= [];
            $response['racers'][$entryNumberKey] += $entryNumber;
            $response['racers'][$entryNumberKey] += $place;
            $response['racers'][$entryNumberKey] += $number;
            $response['racers'][$entryNumberKey] += $name;
        }

        ksort($response['racers'], SORT_NUMERIC);

        return $response;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @return array{
     *     payouts?: array{
     *         trifecta?: list<array{combination: non-empty-string, amount: int<0, max>}>,
     *         trio?: list<array{combination: non-empty-string, amount: int<0, max>}>,
     *         exacta?: list<array{combination: non-empty-string, amount: int<0, max>}>,
     *         quinella?: list<array{combination: non-empty-string, amount: int<0, max>}>,
     *         quinella_place?: list<array{combination: non-empty-string, amount: int<0, max>}>,
     *         win?: list<array{combination: non-empty-string, amount: int<0, max>}>,
     *         place?: list<array{combination: non-empty-string, amount: int<0, max>}>,
     *     }
     * }
     */
    private static function scrapePayouts(Crawler $scraper): array
    {
        $response = [];

        $combinations = self::scrapeAllCombinations($scraper);
        $amounts = self::scrapeAllAmounts($scraper);

        foreach ($combinations as $name => $values) {
            foreach ($values as $index => $value) {
                if (!isset($response['payouts'][$name])) {
                    $response['payouts'][$name] = [];
                }

                if ($value !== '' && $amounts[$name][$index] !== null) {
                    $response['payouts'][$name][] = [
                        'combination' => $value,
                        'amount' => $amounts[$name][$index],
                    ];
                }
            }
        }

        return $response;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @return array{
     *     trifecta: list<string>,
     *     trio: list<string>,
     *     exacta: list<string>,
     *     quinella: list<string>,
     *     quinella_place: list<string>,
     *     win: list<string>,
     *     place: list<string>,
     * }
     */
    private static function scrapeAllCombinations(Crawler $scraper): array
    {
        return [
            'trifecta' => self::scrapeCombinations($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[1]/tr[1]/td[2]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[1]/tr[2]/td[1]/div/div/span[%d]',
            ], range(1, 5)),
            'trio' => self::scrapeCombinations($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[2]/tr[1]/td[2]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[2]/tr[2]/td[1]/div/div/span[%d]',
            ], range(1, 5)),
            'exacta' => self::scrapeCombinations($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[3]/tr[1]/td[2]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[3]/tr[2]/td[1]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[3]/tr[3]/td[1]/div/div/span[%d]',
            ], range(1, 3)),
            'quinella' => self::scrapeCombinations($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[4]/tr[1]/td[2]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[4]/tr[2]/td[1]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[4]/tr[3]/td[1]/div/div/span[%d]',
            ], range(1, 3)),
            'quinella_place' => self::scrapeCombinations($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[1]/td[2]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[2]/td[1]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[3]/td[1]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[4]/td[1]/div/div/span[%d]',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[5]/td[1]/div/div/span[%d]',
            ], range(1, 3)),
            'win' => self::scrapeCombinations($scraper, [
                '%s//div[2]/div[6]/div[1]/div/table/tbody[6]/tr[1]/td[2]/div/div/span[%d]',
                '%s//div[2]/div[6]/div[1]/div/table/tbody[6]/tr[2]/td[1]/div/div/span[%d]',
            ], range(1, 1)),
            'place' => self::scrapeCombinations($scraper, [
                '%s//div[2]/div[6]/div[1]/div/table/tbody[7]/tr[1]/td[2]/div/div/span[%d]',
                '%s//div[2]/div[6]/div[1]/div/table/tbody[7]/tr[2]/td[1]/div/div/span[%d]',
                '%s//div[2]/div[6]/div[1]/div/table/tbody[7]/tr[3]/td[1]/div/div/span[%d]',
            ], range(1, 1)),
        ];
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @param list<non-empty-string> $templates
     * @param list<int<0, max>> $indexes
     * @return list<string>
     */
    private static function scrapeCombinations(Crawler $scraper, array $templates, array $indexes): array
    {
        $response = [];

        foreach ($templates as $template) {
            $values = [];

            foreach ($indexes as $index) {
                $values[] = Filter::byXPath($scraper, sprintf($template, self::$baseXPath, $index));
            }

            $response[] = implode($values);
        }

        return $response;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @return array{
     *     trifecta: list<?int<0, max>>,
     *     trio: list<?int<0, max>>,
     *     exacta: list<?int<0, max>>,
     *     quinella: list<?int<0, max>>,
     *     quinella_place: list<?int<0, max>>,
     *     win: list<?int<0, max>>,
     *     place: list<?int<0, max>>,
     * }
     */
    private static function scrapeAllAmounts(Crawler $scraper): array
    {
        return [
            'trifecta' => self::scrapeAmounts($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[1]/tr[1]/td[3]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[1]/tr[2]/td[2]/span',
            ]),
            'trio' => self::scrapeAmounts($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[2]/tr[1]/td[3]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[2]/tr[2]/td[2]/span',
            ]),
            'exacta' => self::scrapeAmounts($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[3]/tr[1]/td[3]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[3]/tr[2]/td[2]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[3]/tr[3]/td[2]/span',
            ]),
            'quinella' => self::scrapeAmounts($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[4]/tr[1]/td[3]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[4]/tr[2]/td[2]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[4]/tr[3]/td[2]/span',
            ]),
            'quinella_place' => self::scrapeAmounts($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[1]/td[3]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[2]/td[2]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[3]/td[2]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[4]/td[2]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[5]/tr[5]/td[2]/span',
            ]),
            'win' => self::scrapeAmounts($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[6]/tr[1]/td[3]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[6]/tr[2]/td[2]/span',
            ]),
            'place' => self::scrapeAmounts($scraper, [
                '%s/div[2]/div[6]/div[1]/div/table/tbody[7]/tr[1]/td[3]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[7]/tr[2]/td[2]/span',
                '%s/div[2]/div[6]/div[1]/div/table/tbody[7]/tr[3]/td[2]/span',
            ]),
        ];
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @param list<non-empty-string> $templates
     * @return list<?int<0, max>>
     */
    private static function scrapeAmounts(Crawler $scraper, array $templates): array
    {
        return array_map(function (string $template) use ($scraper) {
            $value = Filter::byXPath($scraper, sprintf($template, self::$baseXPath));

            $value = str_replace(',', '', str_replace('¥', '', $value ?? ''));

            $value = Converter::toInt($value);

            return $value >= 0 ? $value : null;
        }, $templates);
    }
}
