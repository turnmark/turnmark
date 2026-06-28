<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Enums;

use ValueError;

/**
 * @author shimomo
 */
enum Place: int
{
    case P1 = 1;
    case P2 = 2;
    case P3 = 3;
    case P4 = 4;
    case P5 = 5;
    case P6 = 6;
    case 妨 = 7;
    case エ = 8;
    case 転 = 9;
    case 落 = 10;
    case 沈 = 11;
    case 不 = 12;
    case 失 = 13;
    case F = 14;
    case L = 15;
    case 欠 = 16;
    case _ = 17;

    /**
     * @return non-empty-string
     */
    public function name(): string
    {
        return match ($this) {
            self::P1 => '1着',
            self::P2 => '2着',
            self::P3 => '3着',
            self::P4 => '4着',
            self::P5 => '5着',
            self::P6 => '6着',
            self::妨 => '妨害失格',
            self::エ => 'エンスト失格',
            self::転 => '転覆失格',
            self::落 => '落水失格',
            self::沈 => '沈没失格',
            self::不 => '不完走失格',
            self::失 => '失格',
            self::F => 'フライング',
            self::L => '出遅れ',
            self::欠 => '欠場',
            self::_ => '_',
        };
    }

    /**
     * @return non-empty-string
     */
    public function shortName(): string
    {
        return match ($this) {
            self::P1 => '1',
            self::P2 => '2',
            self::P3 => '3',
            self::P4 => '4',
            self::P5 => '5',
            self::P6 => '6',
            self::妨 => '妨',
            self::エ => 'エ',
            self::転 => '転',
            self::落 => '落',
            self::沈 => '沈',
            self::不 => '不',
            self::失 => '失',
            self::F => 'F',
            self::L => 'L',
            self::欠 => '欠',
            self::_ => '_',
        };
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
