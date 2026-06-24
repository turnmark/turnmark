<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Filters;

use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Converters\Converter;

/**
 * @author shimomo
 */
final class Filter
{
    /**
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @param string $xpath
     * @return ?string
     */
    public static function byXPath(Crawler $scraper, string $xpath): ?string
    {
        if (!$scraper->filterXPath($xpath)->count()) {
            return null;
        }

        $value = $scraper->filterXPath($xpath)->text();

        $value = Converter::toKana($value);

        return $value === null ? null : mb_trim($value);
    }
}
