<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Scrapers;

use Carbon\CarbonImmutable as Carbon;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class ResultScraperDataProvider
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
                'arguments' => [Carbon::parse('2026-05-31'), 6, 12],
                'expected' => [
                    'date' => '2026-05-31',
                    'stadium_number' => 6,
                    'race_number' => 12,
                    'wind_speed_source' => '3m',
                    'wind_speed' => 3,
                    'wind_direction_number_source' => '西',
                    'wind_direction_number' => 13,
                    'wave_height_source' => '2cm',
                    'wave_height' => 2,
                    'weather_number_source' => '晴',
                    'weather_number' => 1,
                    'air_temperature_source' => '22.0℃',
                    'air_temperature' => 22.0,
                    'water_temperature_source' => '23.0℃',
                    'water_temperature' => 23.0,
                    'technique_number_source' => '逃げ',
                    'technique_number' => 1,
                    'racers' => [
                        1 => [
                            'entry_number' => 1,
                            'course_number' => 1,
                            'start_timing_source' => '.04',
                            'start_timing' => 0.04,
                            'place_number_source' => '1',
                            'place_number' => 1,
                            'number_source' => '4686',
                            'number' => 4686,
                            'name' => '丸野 一樹',
                        ],
                        2 => [
                            'entry_number' => 2,
                            'course_number' => 2,
                            'start_timing_source' => '.08',
                            'start_timing' => 0.08,
                            'place_number_source' => '6',
                            'place_number' => 6,
                            'number_source' => '5121',
                            'number' => 5121,
                            'name' => '定松 勇樹',
                        ],
                        3 => [
                            'entry_number' => 3,
                            'course_number' => 4,
                            'start_timing_source' => '.09',
                            'start_timing' => 0.09,
                            'place_number_source' => '4',
                            'place_number' => 4,
                            'number_source' => '4503',
                            'number' => 4503,
                            'name' => '上野 真之介',
                        ],
                        4 => [
                            'entry_number' => 4,
                            'course_number' => 3,
                            'start_timing_source' => '.09',
                            'start_timing' => 0.09,
                            'place_number_source' => '3',
                            'place_number' => 3,
                            'number_source' => '4500',
                            'number' => 4500,
                            'name' => '山田 康二',
                        ],
                        5 => [
                            'entry_number' => 5,
                            'course_number' => 5,
                            'start_timing_source' => '.11',
                            'start_timing' => 0.11,
                            'place_number_source' => '2',
                            'place_number' => 2,
                            'number_source' => '4344',
                            'number' => 4344,
                            'name' => '新田 雄史',
                        ],
                        6 => [
                            'entry_number' => 6,
                            'course_number' => 6,
                            'start_timing_source' => '.18',
                            'start_timing' => 0.18,
                            'place_number_source' => '5',
                            'place_number' => 5,
                            'number_source' => '4573',
                            'number' => 4573,
                            'name' => '佐藤 翼',
                        ],
                    ],
                    'payouts' => [
                        'trifecta' => [
                            [
                                'combination' => '1-5-4',
                                'amount' => 4430,
                            ],
                        ],
                        'trio' => [
                            [
                                'combination' => '1=4=5',
                                'amount' => 1550,
                            ],
                        ],
                        'exacta' => [
                            [
                                'combination' => '1-5',
                                'amount' => 950,
                            ],
                        ],
                        'quinella' => [
                            [
                                'combination' => '1=5',
                                'amount' => 840,
                            ],
                        ],
                        'quinella_place' => [
                            [
                                'combination' => '1=5',
                                'amount' => 320,
                            ],
                            [
                                'combination' => '1=4',
                                'amount' => 240,
                            ],
                            [
                                'combination' => '4=5',
                                'amount' => 790,
                            ],
                        ],
                        'win' => [
                            [
                                'combination' => '1',
                                'amount' => 120,
                            ],
                        ],
                        'place' => [
                            [
                                'combination' => '1',
                                'amount' => 110,
                            ],
                            [
                                'combination' => '5',
                                'amount' => 360,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'arguments' => [Carbon::parse('2026-05-18'), 13, 12],
                'expected' => [
                    'date' => '2026-05-18',
                    'stadium_number' => 13,
                    'race_number' => 12,
                    'wind_speed_source' => null,
                    'wind_speed' => null,
                    'wind_direction_number_source' => null,
                    'wind_direction_number' => null,
                    'wave_height_source' => null,
                    'wave_height' => null,
                    'weather_number_source' => null,
                    'weather_number' => null,
                    'air_temperature_source' => null,
                    'air_temperature' => null,
                    'water_temperature_source' => null,
                    'water_temperature' => null,
                    'technique_number_source' => null,
                    'technique_number' => null,
                    'racers' => [
                        1 => [
                            'entry_number' => 1,
                            'course_number' => null,
                            'start_timing_source' => null,
                            'start_timing' => null,
                            'place_number_source' => null,
                            'place_number' => null,
                            'number_source' => null,
                            'number' => null,
                            'name' => null,
                        ],
                        2 => [
                            'entry_number' => 2,
                            'course_number' => null,
                            'start_timing_source' => null,
                            'start_timing' => null,
                            'place_number_source' => null,
                            'place_number' => null,
                            'number_source' => null,
                            'number' => null,
                            'name' => null,
                        ],
                        3 => [
                            'entry_number' => 3,
                            'course_number' => null,
                            'start_timing_source' => null,
                            'start_timing' => null,
                            'place_number_source' => null,
                            'place_number' => null,
                            'number_source' => null,
                            'number' => null,
                            'name' => null,
                        ],
                        4 => [
                            'entry_number' => 4,
                            'course_number' => null,
                            'start_timing_source' => null,
                            'start_timing' => null,
                            'place_number_source' => null,
                            'place_number' => null,
                            'number_source' => null,
                            'number' => null,
                            'name' => null,
                        ],
                        5 => [
                            'entry_number' => 5,
                            'course_number' => null,
                            'start_timing_source' => null,
                            'start_timing' => null,
                            'place_number_source' => null,
                            'place_number' => null,
                            'number_source' => null,
                            'number' => null,
                            'name' => null,
                        ],
                        6 => [
                            'entry_number' => 6,
                            'course_number' => null,
                            'start_timing_source' => null,
                            'start_timing' => null,
                            'place_number_source' => null,
                            'place_number' => null,
                            'number_source' => null,
                            'number' => null,
                            'name' => null,
                        ],
                    ],
                    'payouts' => [
                        'trifecta' => [],
                        'trio' => [],
                        'exacta' => [],
                        'quinella' => [],
                        'quinella_place' => [],
                        'win' => [],
                        'place' => [],
                    ],
                ],
            ],
        ];
    }
}
