<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Enums;

use ValueError;

/**
 * @author shimomo
 */
enum Weather: int
{
    case 晴 = 1;
    case 曇 = 2;
    case 雨 = 3;
    case 雪 = 4;
    case 霧 = 5;

    /**
     * @return non-empty-string
     */
    public function name(): string
    {
        return match ($this) {
            Weather::晴 => '晴',
            Weather::曇 => '曇り',
            Weather::雨 => '雨',
            Weather::雪 => '雪',
            Weather::霧 => '霧',
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
