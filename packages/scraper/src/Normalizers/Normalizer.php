<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Normalizers;

use Turnmark\Scraper\Converters\Converter;

/**
 * @author shimomo
 */
final class Normalizer
{
    /**
     * @var array<non-empty-string, bool>
     */
    private static array $defaultOptions = [
        'shouldRemoveAllSpaces' => false,
        'shouldRemoveAllNumbers' => false,
        'shouldRemoveAllNotNumbers' => false,
    ];

    /**
     * @param int|float|string|array|null $data
     * @param array<string, bool> $options
     * @return int|float|string|array|null
     */
    public static function normalize(
        int|float|string|array|null $data,
        array $options = []
    ): int|float|string|array|null {
        if (is_float($data) || is_int($data) || is_null($data)) {
            return $data;
        }

        if (is_numeric($data) && str_contains($data, '.')) {
            return Converter::toFloat($data);
        }

        if (is_numeric($data) && !str_contains($data, '.')) {
            return Converter::toInt($data);
        }

        if (is_array($data)) {
            $normalizer = fn(int|float|string|array|null $value): int|float|string|array|null
                => self::normalize($value, $options);

            return array_map($normalizer, $data);
        }

        $options = array_merge(
            self::$defaultOptions,
            Converter::toCamelCaseKeys($options)
        );

        $data = Converter::toStringStrict($data);
        $data = self::normalizeSpaces($data, $options);
        $data = self::normalizeNumbers($data, $options);
        $data = self::normalizeNotNumbers($data, $options);

        return $data;
    }

    /**
     * @param string $value
     * @param array<string, bool> $options
     * @return string
     */
    private static function normalizeSpaces(string $value, array $options): string
    {
        if ($options['shouldRemoveAllSpaces']) {
            return preg_replace('/\s+/u', '', $value) ?? $value;
        } else {
            return preg_replace('/\s+/u', ' ', $value) ?? $value;
        }
    }

    /**
     * @param string $value
     * @param array<string, bool> $options
     * @return string
     */
    private static function normalizeNumbers(string $value, array $options): string
    {
        if ($options['shouldRemoveAllNumbers']) {
            return preg_replace('/\d/u', '', $value) ?? $value;
        } else {
            return $value;
        }
    }

    /**
     * @param string $value
     * @param array<string, bool> $options
     * @return string
     */
    private static function normalizeNotNumbers(string $value, array $options): string
    {
        if ($options['shouldRemoveAllNotNumbers']) {
            return preg_replace('/\D/u', '', $value) ?? $value;
        } else {
            return $value;
        }
    }
}
