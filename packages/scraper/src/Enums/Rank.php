<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Enums;

use ValueError;

/**
 * @author shimomo
 */
enum Rank: int
{
    case A1 = 1;
    case A2 = 2;
    case B1 = 3;
    case B2 = 4;

    /**
     * @return non-empty-string
     */
    public function name(): string
    {
        return match ($this) {
            self::A1 => 'A1級',
            self::A2 => 'A2級',
            self::B1 => 'B1級',
            self::B2 => 'B2級',
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
