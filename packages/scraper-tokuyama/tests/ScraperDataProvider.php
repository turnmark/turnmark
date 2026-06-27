<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tokuyama\Tests;

use Carbon\CarbonImmutable as Carbon;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 * @psalm-import-type BatchArguments from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 * @psalm-import-type ExpectedByRaceNumber from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
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
                'arguments' => [Carbon::parse('2026-06-25'), 12],
                'expected' => [
                    'date' => '2026-06-25',
                    'stadium_number' => 18,
                    'race_number' => 12,
                    'racers' => [
                        1 => [
                            'entry_number' => 1,
                            'name' => '吉田 俊彦',
                            'exhibition_time' => 6.92,
                            'lap_time' => 38.06,
                            'turn_time' => 11.74,
                        ],
                        2 => [
                            'entry_number' => 2,
                            'name' => '川崎 智幸',
                            'exhibition_time' => 6.94,
                            'lap_time' => 37.52,
                            'turn_time' => 11.45,
                        ],
                        3 => [
                            'entry_number' => 3,
                            'name' => '竹田 和哉',
                            'exhibition_time' => 6.93,
                            'lap_time' => 37.88,
                            'turn_time' => 11.77,
                        ],
                        4 => [
                            'entry_number' => 4,
                            'name' => '木谷 賢太',
                            'exhibition_time' => 6.96,
                            'lap_time' => 37.82,
                            'turn_time' => 11.54,
                        ],
                        5 => [
                            'entry_number' => 5,
                            'name' => '高橋 竜矢',
                            'exhibition_time' => 6.97,
                            'lap_time' => 37.99,
                            'turn_time' => 11.40,
                        ],
                        6 => [
                            'entry_number' => 6,
                            'name' => '島田 賢人',
                            'exhibition_time' => 6.89,
                            'lap_time' => 38.20,
                            'turn_time' => 11.94,
                        ],
                    ],
                ],
            ],
            [
                'arguments' => [Carbon::parse('2026-06-23'), 12],
                'expected' => [
                    'date' => '2026-06-23',
                    'stadium_number' => 18,
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
                'arguments' => [Carbon::parse('2026-06-25'), [12]],
                'expected' => [
                    12 => [
                        'date' => '2026-06-25',
                        'stadium_number' => 18,
                        'race_number' => 12,
                        'racers' => [
                            1 => [
                                'entry_number' => 1,
                                'name' => '吉田 俊彦',
                                'exhibition_time' => 6.92,
                                'lap_time' => 38.06,
                                'turn_time' => 11.74,
                            ],
                            2 => [
                                'entry_number' => 2,
                                'name' => '川崎 智幸',
                                'exhibition_time' => 6.94,
                                'lap_time' => 37.52,
                                'turn_time' => 11.45,
                            ],
                            3 => [
                                'entry_number' => 3,
                                'name' => '竹田 和哉',
                                'exhibition_time' => 6.93,
                                'lap_time' => 37.88,
                                'turn_time' => 11.77,
                            ],
                            4 => [
                                'entry_number' => 4,
                                'name' => '木谷 賢太',
                                'exhibition_time' => 6.96,
                                'lap_time' => 37.82,
                                'turn_time' => 11.54,
                            ],
                            5 => [
                                'entry_number' => 5,
                                'name' => '高橋 竜矢',
                                'exhibition_time' => 6.97,
                                'lap_time' => 37.99,
                                'turn_time' => 11.40,
                            ],
                            6 => [
                                'entry_number' => 6,
                                'name' => '島田 賢人',
                                'exhibition_time' => 6.89,
                                'lap_time' => 38.20,
                                'turn_time' => 11.94,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'arguments' => [Carbon::parse('2026-06-23'), [12]],
                'expected' => [
                    12 => [
                        'date' => '2026-06-23',
                        'stadium_number' => 18,
                        'race_number' => 12,
                        'racers' => [],
                    ],
                ],
            ],
        ];
    }
}
