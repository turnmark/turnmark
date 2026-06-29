<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Mikuni;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Turnmark\Scraper\Mikuni\Scrapers\TimeScraper;
use Turnmark\Scraper\Scraper as BoatraceScraper;
use Turnmark\Scraper\Validators\Validator;

/**
 * @author shimomo
 */
final class Scraper
{
    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeTime(
        DateTimeInterface|string $date,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        BoatraceScraper::throttle();

        Validator::validateRaceNumber($raceNumber);

        return TimeScraper::scrape($date, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 12>, array<non-empty-string, mixed>>
     */
    public static function scrapeTimeBulk(
        DateTimeInterface|string $date,
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueRaceNumbers = array_unique($raceNumbers ?: BoatraceScraper::getRaceNumbers());
        $totalSteps = count($uniqueRaceNumbers);

        $output = BoatraceScraper::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $progressBar = new ProgressBar($output, $totalSteps);
        $progressBar->setFormat(
            ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
        );
        $progressBar->start();

        foreach ($uniqueRaceNumbers as $raceNumber) {
            $response[$raceNumber] =
                self::scrapeTime($date, $raceNumber, $httpBrowser);

            $progressBar->advance();
        }

        return $response;
    }
}
