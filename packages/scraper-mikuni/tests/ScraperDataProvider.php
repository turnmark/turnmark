<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Mikuni\Tests;

use Carbon\CarbonImmutable as Carbon;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Mikuni\Tests\ScraperPsalmType
 * @psalm-import-type BatchArguments from \Turnmark\Scraper\Mikuni\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Mikuni\Tests\ScraperPsalmType
 * @psalm-import-type ExpectedByRaceNumber from \Turnmark\Scraper\Mikuni\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class ScraperDataProvider
{
    /**
     * @return non-empty-list<
     *     array{
     *         arguments: Arguments,
     *         expected: Expected,
     *     }
     * >
     */
    public static function scrapeTimeProvider(): array
    {
        return [
            [
                'arguments' => [Carbon::parse('2026-06-28'), 12],
                'expected' => [
                    'date' => '2026-06-28',
                    'stadium_number' => 10,
                    'race_number' => 12,
                    'racers' => [
                        1 => [
                            'entry_number' => 1,
                            'name' => '青木 玄太',
                            'exhibition_time' => 6.85,
                            'lap_time' => 36.73,
                            'turn_time' => 5.42,
                            'straight_time' => 6.77,
                        ],
                        2 => [
                            'entry_number' => 2,
                            'name' => '前沢 丈史',
                            'exhibition_time' => 6.84,
                            'lap_time' => 38.78,
                            'turn_time' => 6.97,
                            'straight_time' => 6.87,
                        ],
                        3 => [
                            'entry_number' => 3,
                            'name' => '福田 宗平',
                            'exhibition_time' => 6.86,
                            'lap_time' => 37.48,
                            'turn_time' => 5.75,
                            'straight_time' => 6.75,
                        ],
                        4 => [
                            'entry_number' => 4,
                            'name' => '浜先 真範',
                            'exhibition_time' => 6.91,
                            'lap_time' => 37.22,
                            'turn_time' => 5.65,
                            'straight_time' => 6.87,
                        ],
                        5 => [
                            'entry_number' => 5,
                            'name' => '中村 日向',
                            'exhibition_time' => 6.70,
                            'lap_time' => 36.68,
                            'turn_time' => 5.58,
                            'straight_time' => 6.77,
                        ],
                        6 => [
                            'entry_number' => 6,
                            'name' => '松本 純平',
                            'exhibition_time' => 6.82,
                            'lap_time' => 36.85,
                            'turn_time' => 5.52,
                            'straight_time' => 6.80,
                        ],
                    ],
                ],
            ],
            [
                'arguments' => [Carbon::parse('2026-06-26'), 12],
                'expected' => [
                    'date' => '2026-06-26',
                    'stadium_number' => 10,
                    'race_number' => 12,
                    'racers' => [],
                ],
            ],
        ];
    }

    /**
     * @return non-empty-list<
     *     array{
     *         arguments: BatchArguments,
     *         expected: ExpectedByRaceNumber,
     *     }
     * >
     */
    public static function scrapeTimeBulkProvider(): array
    {
        return [
            [
                'arguments' => [Carbon::parse('2026-06-28'), [12]],
                'expected' => [
                    12 => [
                        'date' => '2026-06-28',
                        'stadium_number' => 10,
                        'race_number' => 12,
                        'racers' => [
                            1 => [
                                'entry_number' => 1,
                                'name' => '青木 玄太',
                                'exhibition_time' => 6.85,
                                'lap_time' => 36.73,
                                'turn_time' => 5.42,
                                'straight_time' => 6.77,
                            ],
                            2 => [
                                'entry_number' => 2,
                                'name' => '前沢 丈史',
                                'exhibition_time' => 6.84,
                                'lap_time' => 38.78,
                                'turn_time' => 6.97,
                                'straight_time' => 6.87,
                            ],
                            3 => [
                                'entry_number' => 3,
                                'name' => '福田 宗平',
                                'exhibition_time' => 6.86,
                                'lap_time' => 37.48,
                                'turn_time' => 5.75,
                                'straight_time' => 6.75,
                            ],
                            4 => [
                                'entry_number' => 4,
                                'name' => '浜先 真範',
                                'exhibition_time' => 6.91,
                                'lap_time' => 37.22,
                                'turn_time' => 5.65,
                                'straight_time' => 6.87,
                            ],
                            5 => [
                                'entry_number' => 5,
                                'name' => '中村 日向',
                                'exhibition_time' => 6.70,
                                'lap_time' => 36.68,
                                'turn_time' => 5.58,
                                'straight_time' => 6.77,
                            ],
                            6 => [
                                'entry_number' => 6,
                                'name' => '松本 純平',
                                'exhibition_time' => 6.82,
                                'lap_time' => 36.85,
                                'turn_time' => 5.52,
                                'straight_time' => 6.80,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'arguments' => [Carbon::parse('2026-06-26'), [12]],
                'expected' => [
                    12 => [
                        'date' => '2026-06-26',
                        'stadium_number' => 10,
                        'race_number' => 12,
                        'racers' => [],
                    ],
                ],
            ],
        ];
    }
}
