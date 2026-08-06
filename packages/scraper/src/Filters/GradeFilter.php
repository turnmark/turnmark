<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Filters;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Reads the grade out of the class attribute of a node. The grade is styled rather than
 * printed, so there is no text to read.
 *
 * @author shimomo
 */
final class GradeFilter
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

        $value = $scraper->filterXPath($xpath)->attr('class');

        if ($value !== null && preg_match('/is-([a-zA-Z0-9]+)/u', $value, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
