# Turnmark Scraper Tamagawa

[![test](https://github.com/turnmark/turnmark/actions/workflows/test.yml/badge.svg)](https://github.com/turnmark/turnmark/actions/workflows/test.yml)
[![php](https://poser.pugx.org/turnmark/scraper-tamagawa/require/php)](https://packagist.org/packages/turnmark/scraper-tamagawa)
[![stable](https://poser.pugx.org/turnmark/scraper-tamagawa/v/stable)](https://packagist.org/packages/turnmark/scraper-tamagawa)
[![license](https://poser.pugx.org/turnmark/scraper-tamagawa/license)](https://packagist.org/packages/turnmark/scraper-tamagawa)

Turnmark Scraper Tamagawa は、ボートレース多摩川の公式サイトからオリジナル展示タイムをスクレイピングするための PHP ライブラリです。

## 📦 Requirements

- php: ^8.4
- turnmark/scraper: ^0.2

## 💾 Installation

```bash
composer require turnmark/scraper-tamagawa
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

use Turnmark\Scraper\Tamagawa\Scraper;

// オリジナル展示タイムを取得
$time = Scraper::scrapeTime('2026-06-28', 12);
$timeBulk = Scraper::scrapeTimeBulk('2026-06-28', [10, 11, 12]);
```

### Scraper::scrapeTime()

```php
// 例: ボートレース多摩川の公式サイトから 2026年06月28日 の 12 レースの出走表を取得
$time = Scraper::scrapeTime('2026-06-28', 12);

print_r($time);
```

<details>
<summary>取得結果</summary>

```php
Array
(
    [date] => 2026-06-28
    [stadium_number] => 5
    [race_number] => 12
    [racers] => Array
        (
            [1] => Array
                (
                    [entry_number] => 1
                    [name] => 濱野谷 憲吾
                    [exhibition_time] => 6.69
                    [lap_time] => 36.61
                    [turn_time] => 5.63
                    [straight_time] => 6.97
                )

            [2] => Array
                (
                    [entry_number] => 2
                    [name] => 戸塚 邦好
                    [exhibition_time] => 6.73
                    [lap_time] => 36.74
                    [turn_time] => 5.47
                    [straight_time] => 7.16
                )

            [3] => Array
                (
                    [entry_number] => 3
                    [name] => 前田 聖文
                    [exhibition_time] => 6.74
                    [lap_time] => 37.49
                    [turn_time] => 5.51
                    [straight_time] => 7.07
                )

            [4] => Array
                (
                    [entry_number] => 4
                    [name] => 吉川 喜継
                    [exhibition_time] => 6.73
                    [lap_time] => 38.15
                    [turn_time] => 5.67
                    [straight_time] => 7.04
                )

            [5] => Array
                (
                    [entry_number] => 5
                    [name] => 藤山 雅弘
                    [exhibition_time] => 6.84
                    [lap_time] => 38.1
                    [turn_time] => 5.78
                    [straight_time] => 7.18
                )

            [6] => Array
                (
                    [entry_number] => 6
                    [name] => 青木 蓮
                    [exhibition_time] => 6.76
                    [lap_time] => 37.73
                    [turn_time] => 5.44
                    [straight_time] => 7.11
                )

        )

)
```

</details>

## ⚠️ Notes

- **スクレイピング対象の公式サイトの構造が変更された場合**、正しくデータを取得できなくなる可能性があります。
- 利用時は対象サイトの利用規約を遵守してください。

## 📄 License

Turnmark Scraper Tamagawa は [MIT license](LICENSE) の元で公開されています。
