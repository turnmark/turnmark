<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Filters;

use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Converters\Converter;

/**
 * Pulls text out of a page by XPath. The text is normalised on the way out, so that what the
 * parsers and enums are matched against does not depend on how the page was typed.
 *
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

    /**
     * Return the text of every matched node in document order. Used for columns whose number of
     * entries varies and cannot be read with fixed indexes, such as the parts exchange list.
     *
     * @param \Symfony\Component\DomCrawler\Crawler $scraper
     * @param string $xpath
     * @return list<string>
     */
    public static function byXPathAsList(Crawler $scraper, string $xpath): array
    {
        return $scraper->filterXPath($xpath)->each(function (Crawler $node): string {
            $value = Converter::toKana($node->text());

            return $value === null ? '' : mb_trim($value);
        });
    }
}
