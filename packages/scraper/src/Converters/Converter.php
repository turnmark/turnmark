<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Converters;

use ValueError;

/**
 * Casts the text pulled out of a page into the types the responses are built from.
 *
 * @author shimomo
 */
final class Converter
{
    /**
     * @param int|float|string|null $value
     * @return ?int
     */
    public static function toInt(int|float|string|null $value): ?int
    {
        return $value !== null ? (int) $value : null;
    }

    /**
     * @param int|float|string $value
     * @return int
     */
    public static function toIntStrict(int|float|string|null $value): int
    {
        return (int) $value;
    }

    /**
     * @param int|float|string|null $value
     * @return int|float|string|null
     */
    public static function toIntOrReturn(int|float|string|null $value): int|float|string|null
    {
        return is_numeric($value) ? (int) $value : $value;
    }

    /**
     * @param int|float|string|null $value
     * @return ?float
     */
    public static function toFloat(int|float|string|null $value): ?float
    {
        return $value !== null ? (float) $value : null;
    }

    /**
     * @param int|float|string $value
     * @return float
     */
    public static function toFloatStrict(int|float|string $value): float
    {
        return (float) $value;
    }

    /**
     * @param int|float|string|null $value
     * @return int|float|string|null
     */
    public static function toFloatOrReturn(int|float|string|null $value): int|float|string|null
    {
        return is_numeric($value) ? (float) $value : $value;
    }

    /**
     * @param int|float|string|null $value
     * @return ?string
     */
    public static function toString(int|float|string|null $value): ?string
    {
        return $value !== null ? (string) $value : null;
    }

    /**
     * @param int|float|string $value
     * @return string
     */
    public static function toStringStrict(int|float|string $value): string
    {
        return (string) $value;
    }

    /**
     * @param int|float|string|null $value
     * @return null
     */
    public static function toNull(int|float|string|null $value): null
    {
        return null;
    }

    /**
     * Folds the width the page happens to use: letters, digits and spaces come down to
     * half-width, kana go up to full-width. Without it the same value read from two pages would
     * not compare equal.
     *
     * @param ?string $value
     * @return ?string
     */
    public static function toKana(?string $value, string $mode = 'KVas'): ?string
    {
        return $value !== null ? mb_convert_kana($value, $mode, 'UTF-8') : null;
    }

    /**
     * @param ?string $value
     * @return ?int
     */
    public static function toDayNumber(?string $value): ?int
    {
        if ($value === null) {
            return null;
        }

        $value = mb_trim($value);

        $value = self::toKana($value);
        $value = self::toInt($value);

        return $value;
    }

    /**
     * @param string $value
     * @return string
     */
    public static function toCamelCase(string $value): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $value))));
    }

    /**
     * @template TValue
     * @param array<string, TValue> $array
     * @return array<string, TValue>
     */
    public static function toCamelCaseKeys(array $array): array
    {
        $response = [];

        foreach ($array as $key => $value) {
            $response[self::toCamelCase($key)] = $value;
        }

        return $response;
    }

    /**
     * A value the enum does not know is not worth interrupting a scrape for, so the resolver is
     * allowed to fail and the caller keeps the source text. Nothing is written to the output:
     * this runs inside a library, and a stray line corrupts whatever the caller is printing.
     *
     * @template T of \UnitEnum
     * @param callable(): ?T $resolver
     * @return ?T
     */
    public static function toEnumOrNull(callable $resolver): ?object
    {
        try {
            return $resolver();
        } catch (ValueError) {
            return null;
        }
    }
}
