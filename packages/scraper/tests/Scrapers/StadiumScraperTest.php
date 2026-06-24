<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Scrapers;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Scrapers\StadiumScraper;

/**
 * @psalm-import-type Date from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type StadiumNumber from \Turnmark\Scraper\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class StadiumScraperTest extends TestCase
{
    /**
     * @param array{Date} $arguments
     * @param array<StadiumNumber, non-empty-string> $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(StadiumScraperDataProvider::class, 'scrapeStadiumProvider')]
    public function testScrape(array $arguments, array $expected): void
    {
        $this->assertSame($expected, StadiumScraper::scrape(...$arguments));
    }
}
