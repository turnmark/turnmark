<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\BatchScraper;

/**
 * @psalm-import-type BatchArguments from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type ExpectedByStadiumNumber from \Turnmark\Scraper\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class BatchScraperTest extends TestCase
{
    /**
     * @param BatchArguments $arguments
     * @param ExpectedByStadiumNumber $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(BatchScraperDataProvider::class, 'scrapeProgramProvider')]
    public function scrapeProgram(array $arguments, array $expected): void
    {
        $this->assertSame($expected, BatchScraper::scrapeProgram(...$arguments, httpBrowser: MockBrowser::create()));
    }
}
