# 🚤 Turnmark Scraper

[![test](https://github.com/turnmark/turnmark/actions/workflows/test.yml/badge.svg)](https://github.com/turnmark/turnmark/actions/workflows/test.yml)
[![php](https://poser.pugx.org/turnmark/scraper/require/php)](https://packagist.org/packages/turnmark/scraper)
[![stable](https://poser.pugx.org/turnmark/scraper/v/stable)](https://packagist.org/packages/turnmark/scraper)
[![license](https://poser.pugx.org/turnmark/scraper/license)](https://packagist.org/packages/turnmark/scraper)

Turnmark Scraper は、ボートレースの公式サイトから出走表、直前情報、オッズ、結果をスクレイピングするための PHP ライブラリです。

## 📦 Requirements

- php: ^8.4
- nesbot/carbon: ^3.8.4
- symfony/browser-kit: ^8.0
- symfony/console: ^8.0
- symfony/css-selector: ^8.0
- symfony/http-client: ^8.0

## 💾 Installation

```bash
composer require turnmark/scraper
```

## ⚡ Usage

### サポートメソッド一覧

| メソッド | 引数 |
|---|---|
| 出走表を取得<br>`Scraper::scrapeProgram($date, $stadiumNumber, $raceNumber)` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumber` : 1〜24<br>`$raceNumber` : 1〜12 |
| 出走表を一括取得<br>`Scraper::scrapeProgramBulk($date [, $stadiumNumbers, $raceNumbers])` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumbers` : [1〜24]（省略時は全場）<br>`$raceNumbers` : [1〜12]（省略時は全レース） |
| 直前情報を取得<br>`Scraper::scrapePreview($date = null, $stadiumNumber = null, $raceNumber = null)` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumber` : 1〜24<br>`$raceNumber` : 1〜12 |
| 直前情報を一括取得<br>`Scraper::scrapePreviewBulk($date [, $stadiumNumbers, $raceNumbers])` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumbers` : [1〜24]（省略時は全場）<br>`$raceNumbers` : [1〜12]（省略時は全レース） |
| オッズを取得<br>`Scraper::scrapeOdds($date = null, $stadiumNumber = null, $raceNumber = null)` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumber` : 1〜24<br>`$raceNumber` : 1〜12 |
| オッズを一括取得<br>`Scraper::scrapeOddsBulk($date [, $stadiumNumbers, $raceNumbers])` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumbers` : [1〜24]（省略時は全場）<br>`$raceNumbers` : [1〜12]（省略時は全レース） |
| 結果を取得<br>`Scraper::scrapeResult($date = null, $stadiumNumber = null, $raceNumber = null)` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumber` : 1〜24<br>`$raceNumber` : 1〜12 |
| 結果を一括取得<br>`Scraper::scrapeResultBulk($date [, $stadiumNumbers, $raceNumbers])` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$stadiumNumbers` : [1〜24]（省略時は全場）<br>`$raceNumbers` : [1〜12]（省略時は全レース） |

### 基本的な使い方

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Turnmark\Scraper\Scraper;

// 出走表を取得
$program = Scraper::scrapeProgram('2026-05-31', 6, 12);
$programBulk = Scraper::scrapeProgramBulk('2026-05-31', [6], [10, 11, 12]);

// 直前情報を取得
$preview = Scraper::scrapePreview('2026-05-31', 6, 12);
$previewBulk = Scraper::scrapePreviewBulk('2026-05-31', [6], [10, 11, 12]);

// オッズを取得
$odds = Scraper::scrapeOdds('2026-05-31', 6, 12);
$oddsBulk = Scraper::scrapeOddsBulk('2026-05-31', [6], [10, 11, 12]);

// 結果を取得
$result = Scraper::scrapeResult('2026-05-31', 6, 12);
$resultBulk = Scraper::scrapeResultBulk('2026-05-31', [6], [10, 11, 12]);
```

### Scraper::scrapeProgram()

```php
// 例: ボートレースの公式サイトから 2026年05月31日 の 浜名湖 12 レースの出走表を取得
$program = Scraper::scrapeProgram('2026-05-31', 6, 12);

print_r($program);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-05-31
    [stadium_number] => 6
    [race_number] => 12
    [closed_at] => 2026-05-31 16:35:00
    [grade_number_source] => SGa
    [grade_number] => 100
    [title] => 第53回ボートレースオールスター
    [subtitle] => 優勝戦
    [distance_source] => 1800m
    [distance] => 1800
    [day_number_source] => 最終日
    [day_number] => 6
    [racers] => Array
        (
            [1] => Array
                (
                    [entry_number] => 1
                    [name] => 丸野 一樹
                    [number] => 4686
                    [rank_number_source] => A1
                    [rank_number] => 1
                    [branch_number_source] => 滋賀
                    [branch_number] => 25
                    [birthplace_number_source] => 京都
                    [birthplace_number] => 26
                    [age_source] => 34歳
                    [age] => 34
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [flying_count_source] => F0
                    [flying_count] => 0
                    [late_count_source] => L0
                    [late_count] => 0
                    [average_start_timing] => 0.15
                    [national_top_1_percent] => 6.7
                    [national_top_2_percent] => 42.02
                    [national_top_3_percent] => 58.82
                    [local_top_1_percent] => 7.71
                    [local_top_2_percent] => 58.82
                    [local_top_3_percent] => 70.59
                    [motor_number] => 23
                    [motor_top_2_percent] => 26.92
                    [motor_top_3_percent] => 38.46
                    [boat_number] => 45
                    [boat_top_2_percent] => 40.41
                    [boat_top_3_percent] => 52.74
                )

            [2] => Array
                (
                    [entry_number] => 2
                    [name] => 定松 勇樹
                    [number] => 5121
                    [rank_number_source] => A1
                    [rank_number] => 1
                    [branch_number_source] => 佐賀
                    [branch_number] => 41
                    [birthplace_number_source] => 福岡
                    [birthplace_number] => 40
                    [age_source] => 25歳
                    [age] => 25
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [flying_count_source] => F0
                    [flying_count] => 0
                    [late_count_source] => L0
                    [late_count] => 0
                    [average_start_timing] => 0.14
                    [national_top_1_percent] => 7.93
                    [national_top_2_percent] => 63.85
                    [national_top_3_percent] => 75.38
                    [local_top_1_percent] => 7.4
                    [local_top_2_percent] => 60
                    [local_top_3_percent] => 70
                    [motor_number] => 61
                    [motor_top_2_percent] => 26.92
                    [motor_top_3_percent] => 57.69
                    [boat_number] => 76
                    [boat_top_2_percent] => 36.42
                    [boat_top_3_percent] => 49.01
                )

            [3] => Array
                (
                    [entry_number] => 3
                    [name] => 上野 真之介
                    [number] => 4503
                    [rank_number_source] => A1
                    [rank_number] => 1
                    [branch_number_source] => 佐賀
                    [branch_number] => 41
                    [birthplace_number_source] => 佐賀
                    [birthplace_number] => 41
                    [age_source] => 38歳
                    [age] => 38
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [flying_count_source] => F0
                    [flying_count] => 0
                    [late_count_source] => L0
                    [late_count] => 0
                    [average_start_timing] => 0.12
                    [national_top_1_percent] => 7.68
                    [national_top_2_percent] => 60.83
                    [national_top_3_percent] => 72.5
                    [local_top_1_percent] => 8.62
                    [local_top_2_percent] => 73.08
                    [local_top_3_percent] => 84.62
                    [motor_number] => 3
                    [motor_top_2_percent] => 66.67
                    [motor_top_3_percent] => 66.67
                    [boat_number] => 15
                    [boat_top_2_percent] => 35.14
                    [boat_top_3_percent] => 50.68
                )

            [4] => Array
                (
                    [entry_number] => 4
                    [name] => 山田 康二
                    [number] => 4500
                    [rank_number_source] => A1
                    [rank_number] => 1
                    [branch_number_source] => 佐賀
                    [branch_number] => 41
                    [birthplace_number_source] => 佐賀
                    [birthplace_number] => 41
                    [age_source] => 38歳
                    [age] => 38
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [flying_count_source] => F0
                    [flying_count] => 0
                    [late_count_source] => L0
                    [late_count] => 0
                    [average_start_timing] => 0.12
                    [national_top_1_percent] => 7.5
                    [national_top_2_percent] => 49.59
                    [national_top_3_percent] => 71.54
                    [local_top_1_percent] => 8.06
                    [local_top_2_percent] => 64.71
                    [local_top_3_percent] => 82.35
                    [motor_number] => 2
                    [motor_top_2_percent] => 44
                    [motor_top_3_percent] => 60
                    [boat_number] => 31
                    [boat_top_2_percent] => 27.34
                    [boat_top_3_percent] => 48.2
                )

            [5] => Array
                (
                    [entry_number] => 5
                    [name] => 新田 雄史
                    [number] => 4344
                    [rank_number_source] => A1
                    [rank_number] => 1
                    [branch_number_source] => 三重
                    [branch_number] => 24
                    [birthplace_number_source] => 三重
                    [birthplace_number] => 24
                    [age_source] => 41歳
                    [age] => 41
                    [weight_source] => 52.2kg
                    [weight] => 52.2
                    [flying_count_source] => F0
                    [flying_count] => 0
                    [late_count_source] => L0
                    [late_count] => 0
                    [average_start_timing] => 0.12
                    [national_top_1_percent] => 7.44
                    [national_top_2_percent] => 47.22
                    [national_top_3_percent] => 70.37
                    [local_top_1_percent] => 7.91
                    [local_top_2_percent] => 72.73
                    [local_top_3_percent] => 90.91
                    [motor_number] => 1
                    [motor_top_2_percent] => 20.59
                    [motor_top_3_percent] => 38.24
                    [boat_number] => 12
                    [boat_top_2_percent] => 33.77
                    [boat_top_3_percent] => 48.34
                )

            [6] => Array
                (
                    [entry_number] => 6
                    [name] => 佐藤 翼
                    [number] => 4573
                    [rank_number_source] => A1
                    [rank_number] => 1
                    [branch_number_source] => 埼玉
                    [branch_number] => 11
                    [birthplace_number_source] => 埼玉
                    [birthplace_number] => 11
                    [age_source] => 37歳
                    [age] => 37
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [flying_count_source] => F1
                    [flying_count] => 1
                    [late_count_source] => L0
                    [late_count] => 0
                    [average_start_timing] => 0.13
                    [national_top_1_percent] => 6.77
                    [national_top_2_percent] => 41.88
                    [national_top_3_percent] => 61.54
                    [local_top_1_percent] => 5.53
                    [local_top_2_percent] => 29.41
                    [local_top_3_percent] => 41.18
                    [motor_number] => 62
                    [motor_top_2_percent] => 45.45
                    [motor_top_3_percent] => 59.09
                    [boat_number] => 43
                    [boat_top_2_percent] => 29.37
                    [boat_top_3_percent] => 50
                )

        )

)
```

</details>

### Scraper::scrapePreview()

```php
// 例: ボートレースの公式サイトから 2026年05月31日 の 浜名湖 12 レースの直前情報を取得
$preview = Scraper::scrapePreview('2026-05-31', 6, 12);

print_r($preview);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-05-31
    [stadium_number] => 6
    [race_number] => 12
    [wind_speed_source] => 3m
    [wind_speed] => 3
    [wind_direction_number_source] => 西
    [wind_direction_number] => 13
    [wave_height_source] => 2cm
    [wave_height] => 2
    [weather_number_source] => 晴
    [weather_number] => 1
    [air_temperature_source] => 23.0℃
    [air_temperature] => 23
    [water_temperature_source] => 23.0℃
    [water_temperature] => 23
    [racers] => Array
        (
            [1] => Array
                (
                    [entry_number] => 1
                    [course_number] => 1
                    [start_timing_source] => .01
                    [start_timing] => 0.01
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [weight_adjustment_source] => 0.0
                    [weight_adjustment] => 0
                    [exhibition_time_source] => 6.66
                    [exhibition_time] => 6.66
                    [tilt_adjustment_source] => 0.0
                    [tilt_adjustment] => 0
                )

            [2] => Array
                (
                    [entry_number] => 2
                    [course_number] => 2
                    [start_timing_source] => .12
                    [start_timing] => 0.12
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [weight_adjustment_source] => 0.0
                    [weight_adjustment] => 0
                    [exhibition_time_source] => 6.60
                    [exhibition_time] => 6.6
                    [tilt_adjustment_source] => 0.0
                    [tilt_adjustment] => 0
                )

            [3] => Array
                (
                    [entry_number] => 3
                    [course_number] => 4
                    [start_timing_source] => F.03
                    [start_timing] => -0.03
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [weight_adjustment_source] => 0.0
                    [weight_adjustment] => 0
                    [exhibition_time_source] => 6.69
                    [exhibition_time] => 6.69
                    [tilt_adjustment_source] => 0.0
                    [tilt_adjustment] => 0
                )

            [4] => Array
                (
                    [entry_number] => 4
                    [course_number] => 3
                    [start_timing_source] => F.03
                    [start_timing] => -0.03
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [weight_adjustment_source] => 0.0
                    [weight_adjustment] => 0
                    [exhibition_time_source] => 6.68
                    [exhibition_time] => 6.68
                    [tilt_adjustment_source] => -0.5
                    [tilt_adjustment] => -0.5
                )

            [5] => Array
                (
                    [entry_number] => 5
                    [course_number] => 5
                    [start_timing_source] => .07
                    [start_timing] => 0.07
                    [weight_source] => 52.2kg
                    [weight] => 52.2
                    [weight_adjustment_source] => 0.0
                    [weight_adjustment] => 0
                    [exhibition_time_source] => 6.69
                    [exhibition_time] => 6.69
                    [tilt_adjustment_source] => 0.0
                    [tilt_adjustment] => 0
                )

            [6] => Array
                (
                    [entry_number] => 6
                    [course_number] => 6
                    [start_timing_source] => .01
                    [start_timing] => 0.01
                    [weight_source] => 52.0kg
                    [weight] => 52
                    [weight_adjustment_source] => 0.0
                    [weight_adjustment] => 0
                    [exhibition_time_source] => 6.58
                    [exhibition_time] => 6.58
                    [tilt_adjustment_source] => 0.0
                    [tilt_adjustment] => 0
                )

        )

)
```

</details>

### Scraper::scrapeOdds()

```php
// 例: ボートレースの公式サイトから 2026年05月31日 の 浜名湖 12 レースのオッズを取得
$odds = Scraper::scrapeOdds('2026-05-31', 6, 12);

print_r($odds);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-05-31
    [stadium_number] => 6
    [race_number] => 12
    [trifecta] => Array
        (
            [1] => Array
                (
                    [2] => Array
                        (
                            [3] => 10.7
                            [4] => 8.6
                            [5] => 15.4
                            [6] => 14.6
                        )

                    [3] => Array
                        (
                            [2] => 16.4
                            [4] => 15.6
                            [5] => 21.3
                            [6] => 26.3
                        )

                    [4] => Array
                        (
                            [2] => 13.6
                            [3] => 19.9
                            [5] => 23.7
                            [6] => 21.3
                        )

                    [5] => Array
                        (
                            [2] => 31.2
                            [3] => 38.4
                            [4] => 44.3
                            [6] => 43.5
                        )

                    [6] => Array
                        (
                            [2] => 54.1
                            [3] => 82.2
                            [4] => 68.7
                            [5] => 92.7
                        )

                )

            [2] => Array
                (
                    [1] => Array
                        (
                            [3] => 41.6
                            [4] => 34.6
                            [5] => 53.4
                            [6] => 47.6
                        )

                    [3] => Array
                        (
                            [1] => 128.6
                            [4] => 145.4
                            [5] => 229.8
                            [6] => 195.3
                        )

                    [4] => Array
                        (
                            [1] => 110.3
                            [3] => 150.6
                            [5] => 216.2
                            [6] => 158
                        )

                    [5] => Array
                        (
                            [1] => 230.8
                            [3] => 393.2
                            [4] => 360.7
                            [6] => 304.8
                        )

                    [6] => Array
                        (
                            [1] => 228.2
                            [3] => 384.5
                            [4] => 314.4
                            [5] => 381.9
                        )

                )

            [3] => Array
                (
                    [1] => Array
                        (
                            [2] => 89.4
                            [4] => 86.4
                            [5] => 92
                            [6] => 125.9
                        )

                    [2] => Array
                        (
                            [1] => 184.1
                            [4] => 193
                            [5] => 271.4
                            [6] => 234.5
                        )

                    [4] => Array
                        (
                            [1] => 190.7
                            [2] => 212.8
                            [5] => 284.5
                            [6] => 240.8
                        )

                    [5] => Array
                        (
                            [1] => 221.9
                            [2] => 358.4
                            [4] => 350.7
                            [6] => 269.6
                        )

                    [6] => Array
                        (
                            [1] => 373.4
                            [2] => 422.1
                            [4] => 397.6
                            [5] => 400.7
                        )

                )

            [4] => Array
                (
                    [1] => Array
                        (
                            [2] => 109.5
                            [3] => 139
                            [5] => 167.4
                            [6] => 149.8
                        )

                    [2] => Array
                        (
                            [1] => 183.5
                            [3] => 233.5
                            [5] => 355.4
                            [6] => 281
                        )

                    [3] => Array
                        (
                            [1] => 264.8
                            [2] => 278.5
                            [5] => 398.3
                            [6] => 340.9
                        )

                    [5] => Array
                        (
                            [1] => 341.3
                            [2] => 475
                            [3] => 501.3
                            [6] => 395
                        )

                    [6] => Array
                        (
                            [1] => 401.6
                            [2] => 478.3
                            [3] => 548.8
                            [5] => 520.3
                        )

                )

            [5] => Array
                (
                    [1] => Array
                        (
                            [2] => 236.4
                            [3] => 247.3
                            [4] => 350.9
                            [6] => 291.4
                        )

                    [2] => Array
                        (
                            [1] => 391.1
                            [3] => 590.4
                            [4] => 701.3
                            [6] => 586.2
                        )

                    [3] => Array
                        (
                            [1] => 348.8
                            [2] => 705.1
                            [4] => 834.2
                            [6] => 658.2
                        )

                    [4] => Array
                        (
                            [1] => 629
                            [2] => 849.6
                            [3] => 882.5
                            [6] => 743.1
                        )

                    [6] => Array
                        (
                            [1] => 530.2
                            [2] => 737.2
                            [3] => 872.6
                            [4] => 871.8
                        )

                )

            [6] => Array
                (
                    [1] => Array
                        (
                            [2] => 353.9
                            [3] => 600.5
                            [4] => 578.8
                            [5] => 610.1
                        )

                    [2] => Array
                        (
                            [1] => 465.1
                            [3] => 919.9
                            [4] => 795.7
                            [5] => 983.4
                        )

                    [3] => Array
                        (
                            [1] => 949.1
                            [2] => 1114
                            [4] => 1077
                            [5] => 1157
                        )

                    [4] => Array
                        (
                            [1] => 848.3
                            [2] => 970
                            [3] => 1126
                            [5] => 1171
                        )

                    [5] => Array
                        (
                            [1] => 819
                            [2] => 1106
                            [3] => 1207
                            [4] => 1139
                        )

                )

        )

    [trio] => Array
        (
            [1] => Array
                (
                    [2] => Array
                        (
                            [3] => 4.8
                            [4] => 4.6
                            [5] => 8.4
                            [6] => 9.4
                        )

                    [3] => Array
                        (
                            [4] => 7.8
                            [5] => 10.4
                            [6] => 17.8
                        )

                    [4] => Array
                        (
                            [5] => 15.5
                            [6] => 15.6
                        )

                    [5] => Array
                        (
                            [6] => 22.4
                        )

                )

            [2] => Array
                (
                    [3] => Array
                        (
                            [4] => 19.7
                            [5] => 39.7
                            [6] => 44.9
                        )

                    [4] => Array
                        (
                            [5] => 47.8
                            [6] => 40.2
                        )

                    [5] => Array
                        (
                            [6] => 60.3
                        )

                )

            [3] => Array
                (
                    [4] => Array
                        (
                            [5] => 41.8
                            [6] => 50.1
                        )

                    [5] => Array
                        (
                            [6] => 58.7
                        )

                )

            [4] => Array
                (
                    [5] => Array
                        (
                            [6] => 68.9
                        )

                )

        )

    [exacta] => Array
        (
            [1] => Array
                (
                    [2] => 3
                    [3] => 5
                    [4] => 5
                    [5] => 9.5
                    [6] => 19.5
                )

            [2] => Array
                (
                    [1] => 11.2
                    [3] => 38.8
                    [4] => 34.7
                    [5] => 58.5
                    [6] => 64.4
                )

            [3] => Array
                (
                    [1] => 23.1
                    [2] => 54.8
                    [4] => 54.5
                    [5] => 67.5
                    [6] => 83.3
                )

            [4] => Array
                (
                    [1] => 31.6
                    [2] => 58
                    [3] => 70.8
                    [5] => 76.5
                    [6] => 101.1
                )

            [5] => Array
                (
                    [1] => 56.4
                    [2] => 105.1
                    [3] => 116
                    [4] => 128.6
                    [6] => 114.4
                )

            [6] => Array
                (
                    [1] => 86.3
                    [2] => 121.5
                    [3] => 160.6
                    [4] => 161.6
                    [5] => 151.7
                )

        )

    [quinella] => Array
        (
            [1] => Array
                (
                    [2] => 2.5
                    [3] => 4.4
                    [4] => 4.5
                    [5] => 8.4
                    [6] => 15
                )

            [2] => Array
                (
                    [3] => 20.2
                    [4] => 19.4
                    [5] => 35.3
                    [6] => 34
                )

            [3] => Array
                (
                    [4] => 24.6
                    [5] => 34.7
                    [6] => 42.5
                )

            [4] => Array
                (
                    [5] => 40.1
                    [6] => 55.3
                )

            [5] => Array
                (
                    [6] => 60.5
                )

        )

    [quinella_place] => Array
        (
            [1] => Array
                (
                    [2] => Array
                        (
                            [lower_limit] => 1.3
                            [upper_limit] => 1.3
                        )

                    [3] => Array
                        (
                            [lower_limit] => 2
                            [upper_limit] => 2.4
                        )

                    [4] => Array
                        (
                            [lower_limit] => 2
                            [upper_limit] => 2.4
                        )

                    [5] => Array
                        (
                            [lower_limit] => 2.7
                            [upper_limit] => 3.4
                        )

                    [6] => Array
                        (
                            [lower_limit] => 2.7
                            [upper_limit] => 3.3
                        )

                )

            [2] => Array
                (
                    [3] => Array
                        (
                            [lower_limit] => 3.5
                            [upper_limit] => 4.9
                        )

                    [4] => Array
                        (
                            [lower_limit] => 3
                            [upper_limit] => 4.2
                        )

                    [5] => Array
                        (
                            [lower_limit] => 4.5
                            [upper_limit] => 6.1
                        )

                    [6] => Array
                        (
                            [lower_limit] => 4.4
                            [upper_limit] => 5.9
                        )

                )

            [3] => Array
                (
                    [4] => Array
                        (
                            [lower_limit] => 5.6
                            [upper_limit] => 6.8
                        )

                    [5] => Array
                        (
                            [lower_limit] => 6.6
                            [upper_limit] => 7.7
                        )

                    [6] => Array
                        (
                            [lower_limit] => 7.9
                            [upper_limit] => 9.2
                        )

                )

            [4] => Array
                (
                    [5] => Array
                        (
                            [lower_limit] => 7.9
                            [upper_limit] => 9.3
                        )

                    [6] => Array
                        (
                            [lower_limit] => 8.5
                            [upper_limit] => 10
                        )

                )

            [5] => Array
                (
                    [6] => Array
                        (
                            [lower_limit] => 11.9
                            [upper_limit] => 13.3
                        )

                )

        )

    [win] => Array
        (
            [1] => 1.2
            [2] => 4.8
            [3] => 7.6
            [4] => 12.9
            [5] => 17.2
            [6] => 19.3
        )

    [place] => Array
        (
            [1] => Array
                (
                    [lower_limit] => 1
                    [upper_limit] => 1.1
                )

            [2] => Array
                (
                    [lower_limit] => 1.6
                    [upper_limit] => 2.5
                )

            [3] => Array
                (
                    [lower_limit] => 1.3
                    [upper_limit] => 2
                )

            [4] => Array
                (
                    [lower_limit] => 3.5
                    [upper_limit] => 5.8
                )

            [5] => Array
                (
                    [lower_limit] => 3.6
                    [upper_limit] => 6
                )

            [6] => Array
                (
                    [lower_limit] => 5.4
                    [upper_limit] => 8.9
                )

        )

)
```

</details>

### Scraper::scrapeResults()

```php
// 例: ボートレースの公式サイトから 2026年05月31日 の 浜名湖 12 レースの結果を取得
$result = Scraper::scrapeResult('2026-05-31', 6, 12);

print_r($result);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-05-31
    [stadium_number] => 6
    [race_number] => 12
    [wind_speed_source] => 3m
    [wind_speed] => 3
    [wind_direction_number_source] => 西
    [wind_direction_number] => 13
    [wave_height_source] => 2cm
    [wave_height] => 2
    [weather_number_source] => 晴
    [weather_number] => 1
    [air_temperature_source] => 22.0℃
    [air_temperature] => 22
    [water_temperature_source] => 23.0℃
    [water_temperature] => 23
    [technique_number_source] => 逃げ
    [technique_number] => 1
    [racers] => Array
        (
            [1] => Array
                (
                    [entry_number] => 1
                    [course_number] => 1
                    [start_timing_source] => .04
                    [start_timing] => 0.04
                    [place_number_source] => 1
                    [place_number] => 1
                    [number_source] => 4686
                    [number] => 4686
                    [name] => 丸野 一樹
                )

            [2] => Array
                (
                    [entry_number] => 2
                    [course_number] => 2
                    [start_timing_source] => .08
                    [start_timing] => 0.08
                    [place_number_source] => 6
                    [place_number] => 6
                    [number_source] => 5121
                    [number] => 5121
                    [name] => 定松 勇樹
                )

            [3] => Array
                (
                    [entry_number] => 3
                    [course_number] => 4
                    [start_timing_source] => .09
                    [start_timing] => 0.09
                    [place_number_source] => 4
                    [place_number] => 4
                    [number_source] => 4503
                    [number] => 4503
                    [name] => 上野 真之介
                )

            [4] => Array
                (
                    [entry_number] => 4
                    [course_number] => 3
                    [start_timing_source] => .09
                    [start_timing] => 0.09
                    [place_number_source] => 3
                    [place_number] => 3
                    [number_source] => 4500
                    [number] => 4500
                    [name] => 山田 康二
                )

            [5] => Array
                (
                    [entry_number] => 5
                    [course_number] => 5
                    [start_timing_source] => .11
                    [start_timing] => 0.11
                    [place_number_source] => 2
                    [place_number] => 2
                    [number_source] => 4344
                    [number] => 4344
                    [name] => 新田 雄史
                )

            [6] => Array
                (
                    [entry_number] => 6
                    [course_number] => 6
                    [start_timing_source] => .18
                    [start_timing] => 0.18
                    [place_number_source] => 5
                    [place_number] => 5
                    [number_source] => 4573
                    [number] => 4573
                    [name] => 佐藤 翼
                )

        )

    [payouts] => Array
        (
            [trifecta] => Array
                (
                    [0] => Array
                        (
                            [combination] => 1-5-4
                            [amount] => 4430
                        )

                )

            [trio] => Array
                (
                    [0] => Array
                        (
                            [combination] => 1=4=5
                            [amount] => 1550
                        )

                )

            [exacta] => Array
                (
                    [0] => Array
                        (
                            [combination] => 1-5
                            [amount] => 950
                        )

                )

            [quinella] => Array
                (
                    [0] => Array
                        (
                            [combination] => 1=5
                            [amount] => 840
                        )

                )

            [quinella_place] => Array
                (
                    [0] => Array
                        (
                            [combination] => 1=5
                            [amount] => 320
                        )

                    [1] => Array
                        (
                            [combination] => 1=4
                            [amount] => 240
                        )

                    [2] => Array
                        (
                            [combination] => 4=5
                            [amount] => 790
                        )

                )

            [win] => Array
                (
                    [0] => Array
                        (
                            [combination] => 1
                            [amount] => 120
                        )

                )

            [place] => Array
                (
                    [0] => Array
                        (
                            [combination] => 1
                            [amount] => 110
                        )

                    [1] => Array
                        (
                            [combination] => 5
                            [amount] => 360
                        )

                )

        )

)
```

</details>

## ⚠️ Notes

- **スクレイピング対象の公式サイトの構造が変更された場合**、正しくデータを取得できなくなる可能性があります。
- 利用時は対象サイトの利用規約を遵守してください。

## 📄 License

Turnmark Scraper は [MIT license](LICENSE) の元で公開されています。
