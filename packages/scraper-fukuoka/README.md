# 🚤 Turnmark Scraper Fukuoka

[![php](https://poser.pugx.org/turnmark/scraper-fukuoka/require/php)](https://packagist.org/packages/turnmark/scraper-fukuoka)
[![stable](https://poser.pugx.org/turnmark/scraper-fukuoka/v/stable)](https://packagist.org/packages/turnmark/scraper-fukuoka)
[![license](https://poser.pugx.org/turnmark/scraper-fukuoka/license)](https://packagist.org/packages/turnmark/scraper-fukuoka)

[![test](https://github.com/turnmark/turnmark/actions/workflows/test.yml/badge.svg)](https://github.com/turnmark/turnmark/actions/workflows/test.yml)

Turnmark Scraper Fukuoka は、ボートレース福岡の公式サイトからオリジナル展示タイムをスクレイピングするための PHP ライブラリです。

## 📦 Requirements

- php: ^8.4
- turnmark/scraper: ^0.3

## 💾 Installation

```bash
composer require turnmark/scraper-fukuoka
```

## ⚡ Usage

### サポートメソッド一覧

| メソッド | 引数 |
|---|---|
| オリジナル展示タイムを取得<br>`Scraper::scrapeTime($date, $raceNumber)` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$raceNumber` : 1〜12 |
| オリジナル展示タイムを一括取得<br>`Scraper::scrapeTimeBulk($date [, $raceNumbers])` | `$date` : DateTimeInterface インスタンスまたは DateTimeInterface 対応日付文字列<br>`$raceNumbers` : [1〜12]（省略時は全レース） |

### 基本的な使い方

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Turnmark\Scraper\Fukuoka\Scraper;

// オリジナル展示タイムを取得
$time = Scraper::scrapeTime('2026-06-22', 12);
$timeBulk = Scraper::scrapeTimeBulk('2026-06-22', [10, 11, 12]);
```

### Scraper::scrapeTime()

```php
// 例: ボートレース福岡の公式サイトから 2026年06月22日 の 12 レースの出走表を取得
$time = Scraper::scrapeTime('2026-06-22', 12);

print_r($time);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-06-22
    [stadium_number] => 22
    [race_number] => 12
    [racers] => Array
        (
            [1] => Array
                (
                    [entry_number] => 1
                    [name] => 上平真二
                    [exhibition_time] => 6.91
                    [lap_time] => 37.24
                    [turn_time] => 5.59
                    [straight_time] => 7.68
                )

            [2] => Array
                (
                    [entry_number] => 2
                    [name] => 古結宏
                    [exhibition_time] => 6.89
                    [lap_time] => 37.6
                    [turn_time] => 5.47
                    [straight_time] => 7.66
                )

            [3] => Array
                (
                    [entry_number] => 3
                    [name] => 作間章
                    [exhibition_time] => 6.88
                    [lap_time] => 37.58
                    [turn_time] => 5.66
                    [straight_time] => 7.57
                )

            [4] => Array
                (
                    [entry_number] => 4
                    [name] => 横澤剛治
                    [exhibition_time] => 6.9
                    [lap_time] => 38.6
                    [turn_time] => 5.6
                    [straight_time] => 7.65
                )

            [5] => Array
                (
                    [entry_number] => 5
                    [name] => 佐藤大介
                    [exhibition_time] => 6.92
                    [lap_time] => 38.21
                    [turn_time] => 5.72
                    [straight_time] => 7.59
                )

            [6] => Array
                (
                    [entry_number] => 6
                    [name] => 飯山泰
                    [exhibition_time] => 6.85
                    [lap_time] => 37.53
                    [turn_time] => 5.57
                    [straight_time] => 7.57
                )

        )

)
```

</details>

## ⚠️ Notes

- **スクレイピング対象の公式サイトの構造が変更された場合**、正しくデータを取得できなくなる可能性があります。
- 利用時は対象サイトの利用規約を遵守してください。

## 📄 License

Turnmark Scraper Fukuoka は [MIT license](LICENSE) の元で公開されています。
