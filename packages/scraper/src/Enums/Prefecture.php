<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Enums;

use ValueError;

/**
 * @author shimomo
 */
enum Prefecture: int
{
    case 北海道 = 1;
    case 青森 = 2;
    case 岩手 = 3;
    case 宮城 = 4;
    case 秋田 = 5;
    case 山形 = 6;
    case 福島 = 7;
    case 茨城 = 8;
    case 栃木 = 9;
    case 群馬 = 10;
    case 埼玉 = 11;
    case 千葉 = 12;
    case 東京 = 13;
    case 神奈川 = 14;
    case 新潟 = 15;
    case 富山 = 16;
    case 石川 = 17;
    case 福井 = 18;
    case 山梨 = 19;
    case 長野 = 20;
    case 岐阜 = 21;
    case 静岡 = 22;
    case 愛知 = 23;
    case 三重 = 24;
    case 滋賀 = 25;
    case 京都 = 26;
    case 大阪 = 27;
    case 兵庫 = 28;
    case 奈良 = 29;
    case 和歌山 = 30;
    case 鳥取 = 31;
    case 島根 = 32;
    case 岡山 = 33;
    case 広島 = 34;
    case 山口 = 35;
    case 徳島 = 36;
    case 香川 = 37;
    case 愛媛 = 38;
    case 高知 = 39;
    case 福岡 = 40;
    case 佐賀 = 41;
    case 長崎 = 42;
    case 熊本 = 43;
    case 大分 = 44;
    case 宮崎 = 45;
    case 鹿児島 = 46;
    case 沖縄 = 47;

    /**
     * @return non-empty-string
     */
    public function name(): string
    {
        return match ($this) {
            self::北海道 => '北海道',
            self::青森 => '青森県',
            self::岩手 => '岩手県',
            self::宮城 => '宮城県',
            self::秋田 => '秋田県',
            self::山形 => '山形県',
            self::福島 => '福島県',
            self::茨城 => '茨城県',
            self::栃木 => '栃木県',
            self::群馬 => '群馬県',
            self::埼玉 => '埼玉県',
            self::千葉 => '千葉県',
            self::東京 => '東京都',
            self::神奈川 => '神奈川県',
            self::新潟 => '新潟県',
            self::富山 => '富山県',
            self::石川 => '石川県',
            self::福井 => '福井県',
            self::山梨 => '山梨県',
            self::長野 => '長野県',
            self::岐阜 => '岐阜県',
            self::静岡 => '静岡県',
            self::愛知 => '愛知県',
            self::三重 => '三重県',
            self::滋賀 => '滋賀県',
            self::京都 => '京都府',
            self::大阪 => '大阪府',
            self::兵庫 => '兵庫県',
            self::奈良 => '奈良県',
            self::和歌山 => '和歌山県',
            self::鳥取 => '鳥取県',
            self::島根 => '島根県',
            self::岡山 => '岡山県',
            self::広島 => '広島県',
            self::山口 => '山口県',
            self::徳島 => '徳島県',
            self::香川 => '香川県',
            self::愛媛 => '愛媛県',
            self::高知 => '高知県',
            self::福岡 => '福岡県',
            self::佐賀 => '佐賀県',
            self::長崎 => '長崎県',
            self::熊本 => '熊本県',
            self::大分 => '大分県',
            self::宮崎 => '宮崎県',
            self::鹿児島 => '鹿児島県',
            self::沖縄 => '沖縄県',
        };
    }

    /**
     * @return non-empty-string
     */
    public function shortName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ?self
     * @throws \ValueError
     */
    public static function fromName(?string $name): ?self
    {
        if ($name === null) {
            return null;
        }

        foreach (self::cases() as $case) {
            if ($case->name() === $name) {
                return $case;
            }
        }

        throw new ValueError(
            "{$name} is not a valid name for enum " . self::class
        );
    }

    /**
     * @param string $shortName
     * @return ?self
     * @throws \ValueError
     */
    public static function fromShortName(?string $shortName): ?self
    {
        if ($shortName === null) {
            return null;
        }

        foreach (self::cases() as $case) {
            if ($case->shortName() === $shortName) {
                return $case;
            }
        }

        throw new ValueError(
            "{$shortName} is not a valid name for enum " . self::class
        );
    }

    /**
     * @return list<array{
     *     number: int,
     *     name: non-empty-string,
     *     short_name: non-empty-string,
     * }>
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'number' => $case->value,
            'name' => $case->name(),
            'short_name' => $case->shortName(),
        ], self::cases());
    }
}
