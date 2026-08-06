<?php

declare(strict_types=1);

namespace Turnmark\Scraper;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Runs one scraper over a range of stadiums and races, reporting progress as it goes. Only
 * the stadiums holding a race on the date are visited, so asking for all 24 costs nothing
 * extra on a quiet day.
 *
 * @author shimomo
 */
final class BatchScraper
{
    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapeProgram(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: Scraper::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: Scraper::getRaceNumbers());

        $activeStadiumNumbers = array_keys(Scraper::scrapeStadium($date, $httpBrowser));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = Scraper::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 出走表のスクレイピングを開始します</info>');

        // A total of zero leaves the bar without a maximum, which makes %estimated% throw.
        $progressBar = null;

        if ($totalSteps > 0) {
            $progressBar = new ProgressBar($output, $totalSteps);
            $progressBar->setFormat(
                ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
            );
            $progressBar->start();
        }

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    Scraper::scrapeProgram($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar?->advance();
            }
        }

        $progressBar?->finish();
        $output->writeln('');
        $output->writeln("<info>✅ 出走表のスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapePreview(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: Scraper::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: Scraper::getRaceNumbers());

        $activeStadiumNumbers = array_keys(Scraper::scrapeStadium($date, $httpBrowser));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = Scraper::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 直前情報のスクレイピングを開始します</info>');

        // A total of zero leaves the bar without a maximum, which makes %estimated% throw.
        $progressBar = null;

        if ($totalSteps > 0) {
            $progressBar = new ProgressBar($output, $totalSteps);
            $progressBar->setFormat(
                ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
            );
            $progressBar->start();
        }

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    Scraper::scrapePreview($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar?->advance();
            }
        }

        $progressBar?->finish();
        $output->writeln('');
        $output->writeln("<info>✅ 直前情報のスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapeOdds(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: Scraper::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: Scraper::getRaceNumbers());

        $activeStadiumNumbers = array_keys(Scraper::scrapeStadium($date, $httpBrowser));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = Scraper::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 オッズのスクレイピングを開始します</info>');

        // A total of zero leaves the bar without a maximum, which makes %estimated% throw.
        $progressBar = null;

        if ($totalSteps > 0) {
            $progressBar = new ProgressBar($output, $totalSteps);
            $progressBar->setFormat(
                ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
            );
            $progressBar->start();
        }

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    Scraper::scrapeOdds($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar?->advance();
            }
        }

        $progressBar?->finish();
        $output->writeln('');
        $output->writeln("<info>✅ オッズのスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 24>> $stadiumNumbers
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 24>, array<int<1, 12>, array<non-empty-string, mixed>>>
     */
    public static function scrapeResult(
        DateTimeInterface|string $date,
        array $stadiumNumbers = [],
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueStadiumNumbers = array_unique($stadiumNumbers ?: Scraper::getStadiumNumbers());
        $uniqueRaceNumbers = array_unique($raceNumbers ?: Scraper::getRaceNumbers());

        $activeStadiumNumbers = array_keys(Scraper::scrapeStadium($date, $httpBrowser));
        $activeUniqueStadiumNumbers = array_intersect($uniqueStadiumNumbers, $activeStadiumNumbers);

        $totalSteps = count($activeUniqueStadiumNumbers) * count($uniqueRaceNumbers);

        $output = Scraper::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 結果のスクレイピングを開始します</info>');

        // A total of zero leaves the bar without a maximum, which makes %estimated% throw.
        $progressBar = null;

        if ($totalSteps > 0) {
            $progressBar = new ProgressBar($output, $totalSteps);
            $progressBar->setFormat(
                ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
            );
            $progressBar->start();
        }

        foreach ($activeUniqueStadiumNumbers as $stadiumNumber) {
            foreach ($uniqueRaceNumbers as $raceNumber) {
                $response[$stadiumNumber][$raceNumber] =
                    Scraper::scrapeResult($date, $stadiumNumber, $raceNumber, $httpBrowser);

                $progressBar?->advance();
            }
        }

        $progressBar?->finish();
        $output->writeln('');
        $output->writeln("<info>✅ 結果のスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }
}
