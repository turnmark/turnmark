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
use Turnmark\Scraper\Filters\GradeFilter;
use Turnmark\Scraper\Parsers\Parser;
use Turnmark\Scraper\Parsers\ProgramParser;

/**
 * @author shimomo
 */
final class ProgramScraper implements Scraper
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

        $scraperFormat = '%s/owpc/pc/race/racelist?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $closeTimeFormat = '%s/div[2]/div[2]/table/tbody/tr[1]/td[%s]';
        $closeTimeXPath = sprintf($closeTimeFormat, self::$baseXPath, $raceNumber + 1);
        $closeTimeSource = Filter::byXPath($scraper, $closeTimeXPath);

        $closedAt = null;
        if ($closeTimeSource !== null) {
            $closedAt = $date->setTimeFromTimeString($closeTimeSource)
                ->format('Y-m-d H:i:s');
        }

        $gradeFormat = '%s/div[1]/div/div[2]';
        $gradeXPath = sprintf($gradeFormat, self::$baseXPath);
        $gradeSource = GradeFilter::byXPath($scraper, $gradeXPath);
        $grade = ProgramParser::parseGrade($gradeSource);

        $titleFormat = '%s/div[1]/div/div[2]/h2';
        $titleXPath = sprintf($titleFormat, self::$baseXPath);
        $titleSource = Filter::byXPath($scraper, $titleXPath);
        $title = ProgramParser::parseTitle($titleSource);

        $subtitleAndDistanceFormat = '%s/div[2]/div[%d]/h3';
        $subtitleAndDistanceXPath = sprintf($subtitleAndDistanceFormat, self::$baseXPath, self::$baseLevel + 3);
        $subtitleAndDistanceSource = Filter::byXPath($scraper, $subtitleAndDistanceXPath);
        $subtitleAndDistance = ProgramParser::parseSubtitleAndDistance($subtitleAndDistanceSource);

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = $stadiumNumber;
        $response['race_number'] = $raceNumber;
        $response['closed_at'] = $closedAt;

        $response += $grade;
        $response += $title;
        $response += $subtitleAndDistance;

        $response += self::resolveDayNumber($scraper);
        $response += self::scrapeRacers($scraper);

        return $response;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @return array{
     *     day_number_source: ?string,
     *     day_number: ?int,
     * }
     */
    private static function resolveDayNumber(Crawler $scraper): array
    {
        $dayNumberSourceFormat = '%s/div[2]/div[1]/ul/li[%s]/span/span';

        foreach (range(1, 14) as $index) {
            $dayNumberSourceXPath = sprintf($dayNumberSourceFormat, self::$baseXPath, $index);
            $dayNumberSource = Filter::byXPath($scraper, $dayNumberSourceXPath);

            if ($dayNumberSource !== null) {
                $dayNumber = match ($dayNumberSource) {
                    '初日' => 1,
                    '最終日' => self::resolveLastDayNumber($scraper, $index),
                    default => preg_match('/[\p{Nd}]+/u', $dayNumberSource, $matches)
                        ? Converter::toDayNumber($matches[0])
                        : null,
                };

                return ['day_number_source' => $dayNumberSource, 'day_number' => $dayNumber];
            }
        }

        return ['day_number_source' => null, 'day_number' => null];
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @param int<1, 14> $index
     * @return ?int
     */
    private static function resolveLastDayNumber(Crawler $scraper, int $index): ?int
    {
        $previousDayNumberSourceFormat = '%s/div[2]/div[1]/ul/li[%s]/a/span';

        foreach (range(1, 14) as $previousIndex) {
            $previousDayNumberSourceXPath = sprintf($previousDayNumberSourceFormat, self::$baseXPath, $index - $previousIndex);
            $previousDayNumberSource = Filter::byXPath($scraper, $previousDayNumberSourceXPath);

            if ($previousDayNumberSource === null) {
                continue;
            }

            if (preg_match('/[\p{Nd}]+/u', $previousDayNumberSource, $matches)) {
                if (is_int($previousDayNumber = Converter::toDayNumber($matches[0]))) {
                    return $previousDayNumber + 1;
                }
            }
        }

        return null;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @return array{
     *     racers: array<int<1, 6>, array{
     *         entry_number?: ?int,
     *         name?: ?string,
     *         number?: ?int,
     *         rank_number_source?: ?string,
     *         rank_number?: ?int,
     *         branch_number_source?: ?string,
     *         branch_number?: ?int,
     *         birthplace_number_source?: ?string,
     *         birthplace_number?: ?int,
     *         age_source?: ?string,
     *         age?: ?int,
     *         weight_source?: ?string,
     *         weight?: ?float,
     *         flying_count_source?: ?string,
     *         flying_count?: ?int,
     *         late_count_source?: ?string,
     *         late_count?: ?int,
     *         average_start_timing?: ?float,
     *         national_win_rate?: ?float,
     *         national_top_2_percent?: ?float,
     *         national_top_3_percent?: ?float,
     *         local_win_rate?: ?float,
     *         local_top_2_percent?: ?float,
     *         local_top_3_percent?: ?float,
     *         motor_number?: ?int,
     *         motor_top_2_percent?: ?float,
     *         motor_top_3_percent?: ?float,
     *         boat_number?: ?int,
     *         boat_top_2_percent?: ?float,
     *         boat_top_3_percent?: ?float,
     *     }>
     * }
     */
    private static function scrapeRacers(Crawler $scraper): array
    {
        $response = ['racers' => []];

        foreach (range(1, 6) as $index) {
            $entryNumberFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[1]';
            $entryNumberXPath = sprintf($entryNumberFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $entryNumberSource = Filter::byXPath($scraper, $entryNumberXPath);
            $entryNumber = Parser::parseEntryNumber($entryNumberSource);

            $nameFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[3]/div[2]/a';
            $nameXPath = sprintf($nameFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $nameSource = Filter::byXPath($scraper, $nameXPath);
            $name = Parser::parseName($nameSource);

            $numberAndRankNumberFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[3]/div[1]';
            $numberAndRankNumberXPath = sprintf($numberAndRankNumberFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $numberAndRankNumberSource = Filter::byXPath($scraper, $numberAndRankNumberXPath);
            $numberAndRankNumber = ProgramParser::parseNumberAndRankNumber($numberAndRankNumberSource);

            $branchNumberAndBirthplaceNumberAndAgeAndWeightFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[3]/div[3]';
            $branchNumberAndBirthplaceNumberAndAgeAndWeightXPath = sprintf($branchNumberAndBirthplaceNumberAndAgeAndWeightFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $branchNumberAndBirthplaceNumberAndAgeAndWeightSource = Filter::byXPath($scraper, $branchNumberAndBirthplaceNumberAndAgeAndWeightXPath);
            $branchNumberAndBirthplaceNumberAndAgeAndWeight = ProgramParser::parseBranchNumberAndBirthplaceNumberAndAgeAndWeight($branchNumberAndBirthplaceNumberAndAgeAndWeightSource);

            $flyingCountAndLateCountAndAverageStartTimingFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[4]';
            $flyingCountAndLateCountAndAverageStartTimingXPath = sprintf($flyingCountAndLateCountAndAverageStartTimingFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $flyingCountAndLateCountAndAverageStartTimingSource = Filter::byXPath($scraper, $flyingCountAndLateCountAndAverageStartTimingXPath);
            $flyingCountAndLateCountAndAverageStartTiming = ProgramParser::parseFlyingCountAndLateCountAndAverageStartTiming($flyingCountAndLateCountAndAverageStartTimingSource);

            $nationalWinRateAndNationalTop23PercentFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[5]';
            $nationalWinRateAndNationalTop23PercentXPath = sprintf($nationalWinRateAndNationalTop23PercentFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $nationalWinRateAndNationalTop23PercentSource = Filter::byXPath($scraper, $nationalWinRateAndNationalTop23PercentXPath);
            $nationalWinRateAndNationalTop23Percent = ProgramParser::parseNationalWinRateAndNationalTop23Percent($nationalWinRateAndNationalTop23PercentSource);

            $localWinRateAndLocalTop23PercentFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[6]';
            $localWinRateAndLocalTop23PercentXPath = sprintf($localWinRateAndLocalTop23PercentFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $localWinRateAndLocalTop23PercentSource = Filter::byXPath($scraper, $localWinRateAndLocalTop23PercentXPath);
            $localWinRateAndLocalTop23Percent = ProgramParser::parseLocalWinRateAndLocalTop23Percent($localWinRateAndLocalTop23PercentSource);

            $motorNumberAndMotorTop23PercentFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[7]';
            $motorNumberAndMotorTop23PercentXPath = sprintf($motorNumberAndMotorTop23PercentFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $motorNumberAndMotorTop23PercentSource = Filter::byXPath($scraper, $motorNumberAndMotorTop23PercentXPath);
            $motorNumberANDMotorTop23Percent = ProgramParser::parseMotorNumberAndMotorTop23Percent($motorNumberAndMotorTop23PercentSource);

            $boatNumberAndBoatTop23PercentFormat = '%s/div[2]/div[%d]/table/tbody[%s]/tr[1]/td[8]';
            $boatNumberAndMotorTop23PercentXPath = sprintf($boatNumberAndBoatTop23PercentFormat, self::$baseXPath, self::$baseLevel + 5, $index);
            $boatNumberAndBoatTop23PercentSource = Filter::byXPath($scraper, $boatNumberAndMotorTop23PercentXPath);
            $boatNumberANDBoatTop23Percent = ProgramParser::parseBoatNumberAndBoatTop23Percent($boatNumberAndBoatTop23PercentSource);

            if (!isset($entryNumber['entry_number'])) {
                $entryNumber['entry_number'] = $index;
            }

            $entryNumberKey = $entryNumber['entry_number'] ?? $index;

            if (!in_array($entryNumberKey, range(1, 6), true)) {
                continue;
            }

            $response['racers'][$entryNumberKey] ??= [];
            $response['racers'][$entryNumberKey] += $entryNumber;
            $response['racers'][$entryNumberKey] += $name;
            $response['racers'][$entryNumberKey] += $numberAndRankNumber;
            $response['racers'][$entryNumberKey] += $branchNumberAndBirthplaceNumberAndAgeAndWeight;
            $response['racers'][$entryNumberKey] += $flyingCountAndLateCountAndAverageStartTiming;
            $response['racers'][$entryNumberKey] += $nationalWinRateAndNationalTop23Percent;
            $response['racers'][$entryNumberKey] += $localWinRateAndLocalTop23Percent;
            $response['racers'][$entryNumberKey] += $motorNumberANDMotorTop23Percent;
            $response['racers'][$entryNumberKey] += $boatNumberANDBoatTop23Percent;
        }

        ksort($response['racers'], SORT_NUMERIC);

        return $response;
    }
}
