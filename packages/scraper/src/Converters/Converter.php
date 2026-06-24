<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Converters;

/**
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
}
