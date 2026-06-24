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
final class OddsScraperDataProvider
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
                    'trifecta' => [
                        1 => [
                            2 => [
                                3 => 10.7,
                                4 => 8.6,
                                5 => 15.4,
                                6 => 14.6,
                            ],
                            3 => [
                                2 => 16.4,
                                4 => 15.6,
                                5 => 21.3,
                                6 => 26.3,
                            ],
                            4 => [
                                2 => 13.6,
                                3 => 19.9,
                                5 => 23.7,
                                6 => 21.3,
                            ],
                            5 => [
                                2 => 31.2,
                                3 => 38.4,
                                4 => 44.3,
                                6 => 43.5,
                            ],
                            6 => [
                                2 => 54.1,
                                3 => 82.2,
                                4 => 68.7,
                                5 => 92.7,
                            ],
                        ],
                        2 => [
                            1 => [
                                3 => 41.6,
                                4 => 34.6,
                                5 => 53.4,
                                6 => 47.6,
                            ],
                            3 => [
                                1 => 128.6,
                                4 => 145.4,
                                5 => 229.8,
                                6 => 195.3,
                            ],
                            4 => [
                                1 => 110.3,
                                3 => 150.6,
                                5 => 216.2,
                                6 => 158.0,
                            ],
                            5 => [
                                1 => 230.8,
                                3 => 393.2,
                                4 => 360.7,
                                6 => 304.8,
                            ],
                            6 => [
                                1 => 228.2,
                                3 => 384.5,
                                4 => 314.4,
                                5 => 381.9,
                            ],
                        ],
                        3 => [
                            1 => [
                                2 => 89.4,
                                4 => 86.4,
                                5 => 92.0,
                                6 => 125.9,
                            ],
                            2 => [
                                1 => 184.1,
                                4 => 193.0,
                                5 => 271.4,
                                6 => 234.5,
                            ],
                            4 => [
                                1 => 190.7,
                                2 => 212.8,
                                5 => 284.5,
                                6 => 240.8,
                            ],
                            5 => [
                                1 => 221.9,
                                2 => 358.4,
                                4 => 350.7,
                                6 => 269.6,
                            ],
                            6 => [
                                1 => 373.4,
                                2 => 422.1,
                                4 => 397.6,
                                5 => 400.7,
                            ],
                        ],
                        4 => [
                            1 => [
                                2 => 109.5,
                                3 => 139.0,
                                5 => 167.4,
                                6 => 149.8,
                            ],
                            2 => [
                                1 => 183.5,
                                3 => 233.5,
                                5 => 355.4,
                                6 => 281.0,
                            ],
                            3 => [
                                1 => 264.8,
                                2 => 278.5,
                                5 => 398.3,
                                6 => 340.9,
                            ],
                            5 => [
                                1 => 341.3,
                                2 => 475.0,
                                3 => 501.3,
                                6 => 395.0,
                            ],
                            6 => [
                                1 => 401.6,
                                2 => 478.3,
                                3 => 548.8,
                                5 => 520.3,
                            ],
                        ],
                        5 => [
                            1 => [
                                2 => 236.4,
                                3 => 247.3,
                                4 => 350.9,
                                6 => 291.4,
                            ],
                            2 => [
                                1 => 391.1,
                                3 => 590.4,
                                4 => 701.3,
                                6 => 586.2,
                            ],
                            3 => [
                                1 => 348.8,
                                2 => 705.1,
                                4 => 834.2,
                                6 => 658.2,
                            ],
                            4 => [
                                1 => 629.0,
                                2 => 849.6,
                                3 => 882.5,
                                6 => 743.1,
                            ],
                            6 => [
                                1 => 530.2,
                                2 => 737.2,
                                3 => 872.6,
                                4 => 871.8,
                            ],
                        ],
                        6 => [
                            1 => [
                                2 => 353.9,
                                3 => 600.5,
                                4 => 578.8,
                                5 => 610.1,
                            ],
                            2 => [
                                1 => 465.1,
                                3 => 919.9,
                                4 => 795.7,
                                5 => 983.4,
                            ],
                            3 => [
                                1 => 949.1,
                                2 => 1114.0,
                                4 => 1077.0,
                                5 => 1157.0,
                            ],
                            4 => [
                                1 => 848.3,
                                2 => 970.0,
                                3 => 1126.0,
                                5 => 1171.0,
                            ],
                            5 => [
                                1 => 819.0,
                                2 => 1106.0,
                                3 => 1207.0,
                                4 => 1139.0,
                            ],
                        ],
                    ],
                    'trio' => [
                        1 => [
                            2 => [
                                3 => 4.8,
                                4 => 4.6,
                                5 => 8.4,
                                6 => 9.4,
                            ],
                            3 => [
                                4 => 7.8,
                                5 => 10.4,
                                6 => 17.8,
                            ],
                            4 => [
                                5 => 15.5,
                                6 => 15.6,
                            ],
                            5 => [
                                6 => 22.4,
                            ],
                        ],
                        2 => [
                            3 => [
                                4 => 19.7,
                                5 => 39.7,
                                6 => 44.9,
                            ],
                            4 => [
                                5 => 47.8,
                                6 => 40.2,
                            ],
                            5 => [
                                6 => 60.3,
                            ],
                        ],
                        3 => [
                            4 => [
                                5 => 41.8,
                                6 => 50.1,
                            ],
                            5 => [
                                6 => 58.7,
                            ],
                        ],
                        4 => [
                            5 => [
                                6 => 68.9,
                            ],
                        ],
                    ],
                    'exacta' => [
                        1 => [
                            2 => 3.0,
                            3 => 5.0,
                            4 => 5.0,
                            5 => 9.5,
                            6 => 19.5,
                        ],
                        2 => [
                            1 => 11.2,
                            3 => 38.8,
                            4 => 34.7,
                            5 => 58.5,
                            6 => 64.4,
                        ],
                        3 => [
                            1 => 23.1,
                            2 => 54.8,
                            4 => 54.5,
                            5 => 67.5,
                            6 => 83.3,
                        ],
                        4 => [
                            1 => 31.6,
                            2 => 58.0,
                            3 => 70.8,
                            5 => 76.5,
                            6 => 101.1,
                        ],
                        5 => [
                            1 => 56.4,
                            2 => 105.1,
                            3 => 116.0,
                            4 => 128.6,
                            6 => 114.4,
                        ],
                        6 => [
                            1 => 86.3,
                            2 => 121.5,
                            3 => 160.6,
                            4 => 161.6,
                            5 => 151.7,
                        ],
                    ],
                    'quinella' => [
                        1 => [
                            2 => 2.5,
                            3 => 4.4,
                            4 => 4.5,
                            5 => 8.4,
                            6 => 15.0,
                        ],
                        2 => [
                            3 => 20.2,
                            4 => 19.4,
                            5 => 35.3,
                            6 => 34.0,
                        ],
                        3 => [
                            4 => 24.6,
                            5 => 34.7,
                            6 => 42.5,
                        ],
                        4 => [
                            5 => 40.1,
                            6 => 55.3,
                        ],
                        5 => [
                            6 => 60.5,
                        ],
                    ],
                    'quinella_place' => [
                        1 => [
                            2 => [
                                'lower_limit' => 1.3,
                                'upper_limit' => 1.3,
                            ],
                            3 => [
                                'lower_limit' => 2.0,
                                'upper_limit' => 2.4,
                            ],
                            4 => [
                                'lower_limit' => 2.0,
                                'upper_limit' => 2.4,
                            ],
                            5 => [
                                'lower_limit' => 2.7,
                                'upper_limit' => 3.4,
                            ],
                            6 => [
                                'lower_limit' => 2.7,
                                'upper_limit' => 3.3,
                            ],
                        ],
                        2 => [
                            3 => [
                                'lower_limit' => 3.5,
                                'upper_limit' => 4.9,
                            ],
                            4 => [
                                'lower_limit' => 3.0,
                                'upper_limit' => 4.2,
                            ],
                            5 => [
                                'lower_limit' => 4.5,
                                'upper_limit' => 6.1,
                            ],
                            6 => [
                                'lower_limit' => 4.4,
                                'upper_limit' => 5.9,
                            ],
                        ],
                        3 => [
                            4 => [
                                'lower_limit' => 5.6,
                                'upper_limit' => 6.8,
                            ],
                            5 => [
                                'lower_limit' => 6.6,
                                'upper_limit' => 7.7,
                            ],
                            6 => [
                                'lower_limit' => 7.9,
                                'upper_limit' => 9.2,
                            ],
                        ],
                        4 => [
                            5 => [
                                'lower_limit' => 7.9,
                                'upper_limit' => 9.3,
                            ],
                            6 => [
                                'lower_limit' => 8.5,
                                'upper_limit' => 10.0,
                            ],
                        ],
                        5 => [
                            6 => [
                                'lower_limit' => 11.9,
                                'upper_limit' => 13.3,
                            ],
                        ],
                    ],
                    'win' => [
                        1 => 1.2,
                        2 => 4.8,
                        3 => 7.6,
                        4 => 12.9,
                        5 => 17.2,
                        6 => 19.3,
                    ],
                    'place' => [
                        1 => [
                            'lower_limit' => 1.0,
                            'upper_limit' => 1.1,
                        ],
                        2 => [
                            'lower_limit' => 1.6,
                            'upper_limit' => 2.5,
                        ],
                        3 => [
                            'lower_limit' => 1.3,
                            'upper_limit' => 2.0,
                        ],
                        4 => [
                            'lower_limit' => 3.5,
                            'upper_limit' => 5.8,
                        ],
                        5 => [
                            'lower_limit' => 3.6,
                            'upper_limit' => 6.0,
                        ],
                        6 => [
                            'lower_limit' => 5.4,
                            'upper_limit' => 8.9,
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
                    'trifecta' => [
                        1 => [
                            2 => [
                                3 => null,
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            3 => [
                                2 => null,
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            4 => [
                                2 => null,
                                3 => null,
                                5 => null,
                                6 => null,
                            ],
                            5 => [
                                2 => null,
                                3 => null,
                                4 => null,
                                6 => null,
                            ],
                            6 => [
                                2 => null,
                                3 => null,
                                4 => null,
                                5 => null,
                            ],
                        ],
                        2 => [
                            1 => [
                                3 => null,
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            3 => [
                                1 => null,
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            4 => [
                                1 => null,
                                3 => null,
                                5 => null,
                                6 => null,
                            ],
                            5 => [
                                1 => null,
                                3 => null,
                                4 => null,
                                6 => null,
                            ],
                            6 => [
                                1 => null,
                                3 => null,
                                4 => null,
                                5 => null,
                            ],
                        ],
                        3 => [
                            1 => [
                                2 => null,
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            2 => [
                                1 => null,
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            4 => [
                                1 => null,
                                2 => null,
                                5 => null,
                                6 => null,
                            ],
                            5 => [
                                1 => null,
                                2 => null,
                                4 => null,
                                6 => null,
                            ],
                            6 => [
                                1 => null,
                                2 => null,
                                4 => null,
                                5 => null,
                            ],
                        ],
                        4 => [
                            1 => [
                                2 => null,
                                3 => null,
                                5 => null,
                                6 => null,
                            ],
                            2 => [
                                1 => null,
                                3 => null,
                                5 => null,
                                6 => null,
                            ],
                            3 => [
                                1 => null,
                                2 => null,
                                5 => null,
                                6 => null,
                            ],
                            5 => [
                                1 => null,
                                2 => null,
                                3 => null,
                                6 => null,
                            ],
                            6 => [
                                1 => null,
                                2 => null,
                                3 => null,
                                5 => null,
                            ],
                        ],
                        5 => [
                            1 => [
                                2 => null,
                                3 => null,
                                4 => null,
                                6 => null,
                            ],
                            2 => [
                                1 => null,
                                3 => null,
                                4 => null,
                                6 => null,
                            ],
                            3 => [
                                1 => null,
                                2 => null,
                                4 => null,
                                6 => null,
                            ],
                            4 => [
                                1 => null,
                                2 => null,
                                3 => null,
                                6 => null,
                            ],
                            6 => [
                                1 => null,
                                2 => null,
                                3 => null,
                                4 => null,
                            ],
                        ],
                        6 => [
                            1 => [
                                2 => null,
                                3 => null,
                                4 => null,
                                5 => null,
                            ],
                            2 => [
                                1 => null,
                                3 => null,
                                4 => null,
                                5 => null,
                            ],
                            3 => [
                                1 => null,
                                2 => null,
                                4 => null,
                                5 => null,
                            ],
                            4 => [
                                1 => null,
                                2 => null,
                                3 => null,
                                5 => null,
                            ],
                            5 => [
                                1 => null,
                                2 => null,
                                3 => null,
                                4 => null,
                            ],
                        ],
                    ],
                    'trio' => [
                        1 => [
                            2 => [
                                3 => null,
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            3 => [
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            4 => [
                                5 => null,
                                6 => null,
                            ],
                            5 => [
                                6 => null,
                            ],
                        ],
                        2 => [
                            3 => [
                                4 => null,
                                5 => null,
                                6 => null,
                            ],
                            4 => [
                                5 => null,
                                6 => null,
                            ],
                            5 => [
                                6 => null,
                            ],
                        ],
                        3 => [
                            4 => [
                                5 => null,
                                6 => null,
                            ],
                            5 => [
                                6 => null,
                            ],
                        ],
                        4 => [
                            5 => [
                                6 => null,
                            ],
                        ],
                    ],
                    'exacta' => [
                        1 => [
                            2 => null,
                            3 => null,
                            4 => null,
                            5 => null,
                            6 => null,
                        ],
                        2 => [
                            1 => null,
                            3 => null,
                            4 => null,
                            5 => null,
                            6 => null,
                        ],
                        3 => [
                            1 => null,
                            2 => null,
                            4 => null,
                            5 => null,
                            6 => null,
                        ],
                        4 => [
                            1 => null,
                            2 => null,
                            3 => null,
                            5 => null,
                            6 => null,
                        ],
                        5 => [
                            1 => null,
                            2 => null,
                            3 => null,
                            4 => null,
                            6 => null,
                        ],
                        6 => [
                            1 => null,
                            2 => null,
                            3 => null,
                            4 => null,
                            5 => null,
                        ],
                    ],
                    'quinella' => [
                        1 => [
                            2 => null,
                            3 => null,
                            4 => null,
                            5 => null,
                            6 => null,
                        ],
                        2 => [
                            3 => null,
                            4 => null,
                            5 => null,
                            6 => null,
                        ],
                        3 => [
                            4 => null,
                            5 => null,
                            6 => null,
                        ],
                        4 => [
                            5 => null,
                            6 => null,
                        ],
                        5 => [
                            6 => null,
                        ],
                    ],
                    'quinella_place' => [
                        1 => [
                            2 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            3 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            4 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            5 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            6 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                        ],
                        2 => [
                            3 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            4 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            5 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            6 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                        ],
                        3 => [
                            4 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            5 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            6 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                        ],
                        4 => [
                            5 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                            6 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                        ],
                        5 => [
                            6 => [
                                'lower_limit' => null,
                                'upper_limit' => null,
                            ],
                        ],
                    ],
                    'win' => [
                        1 => null,
                        2 => null,
                        3 => null,
                        4 => null,
                        5 => null,
                        6 => null,
                    ],
                    'place' => [
                        1 => [
                            'lower_limit' => null,
                            'upper_limit' => null,
                        ],
                        2 => [
                            'lower_limit' => null,
                            'upper_limit' => null,
                        ],
                        3 => [
                            'lower_limit' => null,
                            'upper_limit' => null,
                        ],
                        4 => [
                            'lower_limit' => null,
                            'upper_limit' => null,
                        ],
                        5 => [
                            'lower_limit' => null,
                            'upper_limit' => null,
                        ],
                        6 => [
                            'lower_limit' => null,
                            'upper_limit' => null,
                        ],
                    ],
                ],
            ],
        ];
    }
}
