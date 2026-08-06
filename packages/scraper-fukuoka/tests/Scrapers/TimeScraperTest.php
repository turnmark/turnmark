<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Fukuoka\Tests\Scrapers;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Fukuoka\Scrapers\TimeScraper;
use Turnmark\Scraper\Fukuoka\Tests\MockBrowser;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Fukuoka\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Fukuoka\Tests\ScraperPsalmType
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
    public function scrape(array $arguments, array $expected): void
    {
        $this->assertSame($expected, TimeScraper::scrape(...$arguments, httpBrowser: MockBrowser::create()));
    }
}
