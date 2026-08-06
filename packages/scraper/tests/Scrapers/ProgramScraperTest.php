<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Scrapers;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Scrapers\ProgramScraper;
use Turnmark\Scraper\Tests\MockBrowser;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class ProgramScraperTest extends TestCase
{
    /**
     * @param Arguments $arguments
     * @param Expected $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(ProgramScraperDataProvider::class, 'scrapeProvider')]
    public function scrape(array $arguments, array $expected): void
    {
        $this->assertSame($expected, ProgramScraper::scrape(...$arguments, httpBrowser: MockBrowser::create()));
    }
}
