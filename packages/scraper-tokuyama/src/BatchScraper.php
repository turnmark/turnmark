<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tokuyama;

use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Turnmark\Scraper\Scraper as BoatraceScraper;

/**
 * @author shimomo
 */
final class BatchScraper
{
    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param list<int<1, 12>> $raceNumbers
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<int<1, 12>, array<non-empty-string, mixed>>
     */
    public static function scrapeTime(
        DateTimeInterface|string $date,
        array $raceNumbers = [],
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $response = [];

        $uniqueRaceNumbers = array_unique($raceNumbers ?: BoatraceScraper::getRaceNumbers());
        $totalSteps = count($uniqueRaceNumbers);

        $output = BoatraceScraper::getShowProgress() ? new ConsoleOutput() : new NullOutput();
        $output->writeln('<info>📊 オリジナル展示タイムのスクレイピングを開始します</info>');

        // A total of zero leaves the bar without a maximum, which makes %estimated% throw.
        $progressBar = null;

        if ($totalSteps > 0) {
            $progressBar = new ProgressBar($output, $totalSteps);
            $progressBar->setFormat(
                ' %current%/%max% [%bar%] %percent:3s%% ⏱️ %elapsed:6s% / %estimated:-6s%'
            );
            $progressBar->start();
        }

        foreach ($uniqueRaceNumbers as $raceNumber) {
            $response[$raceNumber] =
                Scraper::scrapeTime($date, $raceNumber, $httpBrowser);

            $progressBar?->advance();
        }

        $progressBar?->finish();
        $output->writeln('');
        $output->writeln("<info>✅ オリジナル展示タイムのスクレイピングが完了しました（{$totalSteps}件）</info>");
        $output->writeln('');

        return $response;
    }
}
