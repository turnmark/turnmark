<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tamagawa\Tests\Scrapers;

use Carbon\CarbonImmutable as Carbon;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tamagawa\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tamagawa\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class TimeScraperDataProvider
{
    /**
     * @return non-empty-list<
     *     array{
     *         arguments: Arguments,
     *         expected: Expected,
     *     }
     * >
     */
    public static function scrapeProvider(): array
    {
        return [
            [
                'arguments' => [Carbon::parse('2026-06-28'), 12],
                'expected' => [
                    'date' => '2026-06-28',
                    'stadium_number' => 5,
                    'race_number' => 12,
                    'racers' => [
                        1 => [
                            'entry_number' => 1,
                            'name' => '濱野谷 憲吾',
                            'exhibition_time' => 6.69,
                            'lap_time' => 36.61,
                            'turn_time' => 5.63,
                            'straight_time' => 6.97,
                        ],
                        2 => [
                            'entry_number' => 2,
                            'name' => '戸塚 邦好',
                            'exhibition_time' => 6.73,
                            'lap_time' => 36.74,
                            'turn_time' => 5.47,
                            'straight_time' => 7.16,
                        ],
                        3 => [
                            'entry_number' => 3,
                            'name' => '前田 聖文',
                            'exhibition_time' => 6.74,
                            'lap_time' => 37.49,
                            'turn_time' => 5.51,
                            'straight_time' => 7.07,
                        ],
                        4 => [
                            'entry_number' => 4,
                            'name' => '吉川 喜継',
                            'exhibition_time' => 6.73,
                            'lap_time' => 38.15,
                            'turn_time' => 5.67,
                            'straight_time' => 7.04,
                        ],
                        5 => [
                            'entry_number' => 5,
                            'name' => '藤山 雅弘',
                            'exhibition_time' => 6.84,
                            'lap_time' => 38.10,
                            'turn_time' => 5.78,
                            'straight_time' => 7.18,
                        ],
                        6 => [
                            'entry_number' => 6,
                            'name' => '青木 蓮',
                            'exhibition_time' => 6.76,
                            'lap_time' => 37.73,
                            'turn_time' => 5.44,
                            'straight_time' => 7.11,
                        ],
                    ],
                ],
            ],
            [
                'arguments' => [Carbon::parse('2026-06-26'), 12],
                'expected' => [
                    'date' => '2026-06-26',
                    'stadium_number' => 5,
                    'race_number' => 12,
                    'racers' => [],
                ],
            ],
        ];
    }
}
