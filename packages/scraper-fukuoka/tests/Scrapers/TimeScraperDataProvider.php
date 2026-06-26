<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Fukuoka\Tests\Scrapers;

use Carbon\CarbonImmutable as Carbon;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Fukuoka\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Fukuoka\Tests\ScraperPsalmType
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
                'arguments' => [Carbon::parse('2026-06-22'), 12],
                'expected' => [
                    'date' => '2026-06-22',
                    'stadium_number' => 22,
                    'race_number' => 12,
                    'racers' => [
                        1 => [
                            'entry_number' => 1,
                            'name' => '上平 真二',
                            'exhibition_time' => 6.91,
                            'lap_time' => 37.24,
                            'turn_time' => 5.59,
                            'straight_time' => 7.68,
                        ],
                        2 => [
                            'entry_number' => 2,
                            'name' => '古結 宏',
                            'exhibition_time' => 6.89,
                            'lap_time' => 37.60,
                            'turn_time' => 5.47,
                            'straight_time' => 7.66,
                        ],
                        3 => [
                            'entry_number' => 3,
                            'name' => '作間 章',
                            'exhibition_time' => 6.88,
                            'lap_time' => 37.58,
                            'turn_time' => 5.66,
                            'straight_time' => 7.57,
                        ],
                        4 => [
                            'entry_number' => 4,
                            'name' => '横澤 剛治',
                            'exhibition_time' => 6.90,
                            'lap_time' => 38.60,
                            'turn_time' => 5.60,
                            'straight_time' => 7.65,
                        ],
                        5 => [
                            'entry_number' => 5,
                            'name' => '佐藤 大介',
                            'exhibition_time' => 6.92,
                            'lap_time' => 38.21,
                            'turn_time' => 5.72,
                            'straight_time' => 7.59,
                        ],
                        6 => [
                            'entry_number' => 6,
                            'name' => '飯山 泰',
                            'exhibition_time' => 6.85,
                            'lap_time' => 37.53,
                            'turn_time' => 5.57,
                            'straight_time' => 7.57,
                        ],
                    ],
                ],
            ],
            [
                'arguments' => [Carbon::parse('2026-06-20'), 12],
                'expected' => [
                    'date' => '2026-06-20',
                    'stadium_number' => 22,
                    'race_number' => 12,
                    'racers' => [],
                ],
            ],
        ];
    }
}
