# Turnmark Scraper Mikuni

[![test](https://github.com/turnmark/turnmark/actions/workflows/test.yml/badge.svg)](https://github.com/turnmark/turnmark/actions/workflows/test.yml)
[![php](https://poser.pugx.org/turnmark/scraper-mikuni/require/php)](https://packagist.org/packages/turnmark/scraper-mikuni)
[![stable](https://poser.pugx.org/turnmark/scraper-mikuni/v/stable)](https://packagist.org/packages/turnmark/scraper-mikuni)
[![license](https://poser.pugx.org/turnmark/scraper-mikuni/license)](https://packagist.org/packages/turnmark/scraper-mikuni)

Turnmark Scraper Mikuni は、ボートレース三国の公式サイトからオリジナル展示タイムをスクレイピングするための PHP ライブラリです。

## 📦 Requirements

- php: ^8.4
- turnmark/scraper: ^0.3

## 💾 Installation

```bash
composer require turnmark/scraper-mikuni
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

use Turnmark\Scraper\Mikuni\Scraper;

// オリジナル展示タイムを取得
$time = Scraper::scrapeTime('2026-06-28', 12);
$timeBulk = Scraper::scrapeTimeBulk('2026-06-28', [10, 11, 12]);
```

### Scraper::scrapeTime()

```php
// 例: ボートレース三国の公式サイトから 2026年06月28日 の 12 レースの出走表を取得
$time = Scraper::scrapeTime('2026-06-28', 12);

print_r($time);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-06-28
    [stadium_number] => 10
    [race_number] => 12
    [racers] => Array
        (
            [1] => Array
                (
                    [entry_number] => 1
                    [name] => 青木 玄太
                    [exhibition_time] => 6.85
                    [lap_time] => 36.73
                    [turn_time] => 5.42
                    [straight_time] => 6.77
                )

            [2] => Array
                (
                    [entry_number] => 2
                    [name] => 前沢 丈史
                    [exhibition_time] => 6.84
                    [lap_time] => 38.78
                    [turn_time] => 6.97
                    [straight_time] => 6.87
                )

            [3] => Array
                (
                    [entry_number] => 3
                    [name] => 福田 宗平
                    [exhibition_time] => 6.86
                    [lap_time] => 37.48
                    [turn_time] => 5.75
                    [straight_time] => 6.75
                )

            [4] => Array
                (
                    [entry_number] => 4
                    [name] => 浜先 真範
                    [exhibition_time] => 6.91
                    [lap_time] => 37.22
                    [turn_time] => 5.65
                    [straight_time] => 6.87
                )

            [5] => Array
                (
                    [entry_number] => 5
                    [name] => 中村 日向
                    [exhibition_time] => 6.7
                    [lap_time] => 36.68
                    [turn_time] => 5.58
                    [straight_time] => 6.77
                )

            [6] => Array
                (
                    [entry_number] => 6
                    [name] => 松本 純平
                    [exhibition_time] => 6.82
                    [lap_time] => 36.85
                    [turn_time] => 5.52
                    [straight_time] => 6.8
                )

        )

)
```

</details>

## ⚠️ Notes

- **スクレイピング対象の公式サイトの構造が変更された場合**、正しくデータを取得できなくなる可能性があります。
- 利用時は対象サイトの利用規約を遵守してください。

## 📄 License

Turnmark Scraper Mikuni は [MIT license](LICENSE) の元で公開されています。
