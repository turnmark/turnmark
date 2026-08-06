<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Scrapers;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Scrapers\StadiumScraper;
use Turnmark\Scraper\Tests\MockBrowser;

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
    public function scrape(array $arguments, array $expected): void
    {
        $this->assertSame($expected, StadiumScraper::scrape(...$arguments, httpBrowser: MockBrowser::create()));
    }

    /**
     * A name the enum does not know is dropped on its own. Every batch method reads this list
     * before anything else, so letting it take the list down would stop the whole run.
     *
     * The fixture is the 2026-05-31 page with the alt of one banner replaced by a name that is
     * not a stadium.
     *
     * @return void
     */
    #[Test]
    public function scrapeDropsAStadiumItCannotRecognise(): void
    {
        $stadiums = StadiumScraper::scrape(
            '2026-05-31',
            MockBrowser::create('index-unknown-stadium.html')
        );

        $this->assertArrayNotHasKey(1, $stadiums);
        $this->assertCount(15, $stadiums);
        $this->assertSame('戸田', $stadiums[2]);
        $this->assertSame('唐津', $stadiums[23]);
    }
}
