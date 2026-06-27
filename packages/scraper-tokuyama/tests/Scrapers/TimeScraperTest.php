<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tokuyama\Tests\Scrapers;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Tokuyama\Scrapers\TimeScraper;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class TimeScraperTest extends TestCase
{
    /**
     * @param Arguments $arguments
     * @param Expected $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(TimeScraperDataProvider::class, 'scrapeProvider')]
    public function testScrape(array $arguments, array $expected): void
    {
        $this->assertSame($expected, TimeScraper::scrape(...$arguments));
    }
}
