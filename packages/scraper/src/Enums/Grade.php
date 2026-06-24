<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Enums;

use ValueError;

/**
 * @author shimomo
 */
enum Grade: int
{
    case SG = 100;
    case G1 = 200;
    case PG1 = 210;
    case G2 = 300;
    case G3 = 400;
    case IPPAN = 500;

    /**
     * @return non-empty-string
     */
    public function name(): string
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
}
