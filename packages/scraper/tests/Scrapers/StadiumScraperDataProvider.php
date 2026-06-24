<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Scrapers;

use Carbon\CarbonImmutable as Carbon;

/**
 * @psalm-import-type Date from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type StadiumNumber from \Turnmark\Scraper\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class StadiumScraperDataProvider
{
    /**
     * @psalm-return non-empty-list<
     *     array{
     *         arguments: array{Date},
     *         expected: array<StadiumNumber, non-empty-string>,
     *     }
     * >
     *
     * @return array
     */
    public static function scrapeStadiumProvider(): array
    {
        return [
            [
                'arguments' => [Carbon::parse('2026-05-31')],
                'expected' => [
                    1 => '桐生',
                    2 => '戸田',
                    3 => '江戸川',
                    5 => '多摩川',
                    6 => '浜名湖',
                    7 => '蒲郡',
                    8 => '常滑',
                    9 => '津',
                    11 => 'びわこ',
                    12 => '住之江',
                    13 => '尼崎',
                    14 => '鳴門',
                    15 => '丸亀',
                    18 => '徳山',
                    20 => '若松',
                    23 => '唐津',
                ],
            ],
        ];
    }
}
