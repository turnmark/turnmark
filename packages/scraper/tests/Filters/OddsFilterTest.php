<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Filters;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Turnmark\Scraper\Filters\OddsFilter;

/**
 * @author shimomo
 */
final class OddsFilterTest extends TestCase
{
    /**
     * @param non-empty-string $cell
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function crawler(string $cell): Crawler
    {
        return new Crawler('<html><body><table><tr><td id="odds">' . $cell . '</td></tr></table></body></html>');
    }

    /**
     * @return non-empty-string
     */
    private function xpath(): string
    {
        return "descendant-or-self::td[@id='odds']";
    }

    /**
     * @return void
     */
    #[Test]
    public function byXPathReadsANumber(): void
    {
        $this->assertSame(1.5, OddsFilter::byXPath($this->crawler('1.5'), $this->xpath()));
        $this->assertSame(2.5, OddsFilter::byXPath($this->crawler(' 2.5 '), $this->xpath()));
    }

    /**
     * A cell holding anything other than a number is not odds of zero. Casting it would read as
     * the lowest odds the site can print, which the caller could not tell from a real one.
     *
     * @return void
     */
    #[Test]
    public function byXPathReportsANonNumericCellAsMissing(): void
    {
        $this->assertNull(OddsFilter::byXPath($this->crawler('欠場'), $this->xpath()));
        $this->assertNull(OddsFilter::byXPath($this->crawler('-'), $this->xpath()));
        $this->assertNull(OddsFilter::byXPath($this->crawler('&nbsp;'), $this->xpath()));
    }

    /**
     * @return void
     */
    #[Test]
    public function byXPathReportsAMissingCellAsMissing(): void
    {
        $this->assertNull(OddsFilter::byXPath($this->crawler('1.5'), "descendant-or-self::td[@id='none']"));
    }

    /**
     * @return void
     */
    #[Test]
    public function byXPathAsRangeReadsBothLimits(): void
    {
        $this->assertSame(
            ['lower_limit' => 1.5, 'upper_limit' => 2.5],
            OddsFilter::byXPathAsRange($this->crawler('1.5-2.5'), $this->xpath())
        );
    }

    /**
     * @return void
     */
    #[Test]
    public function byXPathAsRangeReportsANonNumericLimitAsMissing(): void
    {
        $expected = ['lower_limit' => null, 'upper_limit' => null];

        $this->assertSame($expected, OddsFilter::byXPathAsRange($this->crawler('-'), $this->xpath()));
        $this->assertSame($expected, OddsFilter::byXPathAsRange($this->crawler('欠場-2.5'), $this->xpath()));
        $this->assertSame($expected, OddsFilter::byXPathAsRange($this->crawler('1.5'), $this->xpath()));
    }
}
