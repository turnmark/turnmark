# 🚤 Turnmark Scraper Tokuyama

[![test](https://github.com/turnmark/turnmark/actions/workflows/test.yml/badge.svg)](https://github.com/turnmark/turnmark/actions/workflows/test.yml)
[![php](https://poser.pugx.org/turnmark/scraper-tokuyama/require/php)](https://packagist.org/packages/turnmark/scraper-tokuyama)
[![stable](https://poser.pugx.org/turnmark/scraper-tokuyama/v/stable)](https://packagist.org/packages/turnmark/scraper-tokuyama)
[![license](https://poser.pugx.org/turnmark/scraper-tokuyama/license)](https://packagist.org/packages/turnmark/scraper-tokuyama)

Turnmark Scraper Tokuyama は、ボートレース徳山の公式サイトからオリジナル展示タイムをスクレイピングするための PHP ライブラリです。

## 📦 Requirements

- php: ^8.4
- turnmark/scraper: ^0.3

## 💾 Installation

```bash
composer require turnmark/scraper-tokuyama
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

use Turnmark\Scraper\Tokuyama\Scraper;

// オリジナル展示タイムを取得
$time = Scraper::scrapeTime('2026-06-25', 12);
$timeBulk = Scraper::scrapeTimeBulk('2026-06-25', [10, 11, 12]);
```

### Scraper::scrapeTime()

```php
// 例: ボートレース徳山の公式サイトから 2026年06月25日 の 12 レースの出走表を取得
$time = Scraper::scrapeTime('2026-06-25', 12);

print_r($time);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-06-25
    [stadium_number] => 18
    [race_number] => 12
    [racers] => Array
        (
            [1] => Array
                (
                    [entry_number] => 1
                    [name] => 吉田 俊彦
                    [exhibition_time] => 6.92
                    [lap_time] => 38.06
                    [turn_time] => 11.74
                )

            [2] => Array
                (
                    [entry_number] => 2
                    [name] => 川崎 智幸
                    [exhibition_time] => 6.94
                    [lap_time] => 37.52
                    [turn_time] => 11.45
                )

            [3] => Array
                (
                    [entry_number] => 3
                    [name] => 竹田 和哉
                    [exhibition_time] => 6.93
                    [lap_time] => 37.88
                    [turn_time] => 11.77
                )

            [4] => Array
                (
                    [entry_number] => 4
                    [name] => 木谷 賢太
                    [exhibition_time] => 6.96
                    [lap_time] => 37.82
                    [turn_time] => 11.54
                )

            [5] => Array
                (
                    [entry_number] => 5
                    [name] => 高橋 竜矢
                    [exhibition_time] => 6.97
                    [lap_time] => 37.99
                    [turn_time] => 11.4
                )

            [6] => Array
                (
                    [entry_number] => 6
                    [name] => 島田 賢人
                    [exhibition_time] => 6.89
                    [lap_time] => 38.2
                    [turn_time] => 11.94
                )

        )

)
```

</details>

## ⚠️ Notes

- **スクレイピング対象の公式サイトの構造が変更された場合**、正しくデータを取得できなくなる可能性があります。
- 利用時は対象サイトの利用規約を遵守してください。

## 📄 License

Turnmark Scraper Tokuyama は [MIT license](LICENSE) の元で公開されています。
