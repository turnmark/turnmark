<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Scrapers;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Scrapers\ResultScraper;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class ResultScraperTest extends TestCase
{
    /**
     * @param Arguments $arguments
     * @param Expected $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(ResultScraperDataProvider::class, 'scrapeProvider')]
    public function testScrape(array $arguments, array $expected): void
    {
        $this->assertSame($expected, ResultScraper::scrape(...$arguments));
    }
}
