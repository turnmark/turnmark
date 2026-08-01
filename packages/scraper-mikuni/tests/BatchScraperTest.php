<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Mikuni\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Mikuni\BatchScraper;

/**
 * @psalm-import-type BatchArguments from \Turnmark\Scraper\Mikuni\Tests\ScraperPsalmType
 * @psalm-import-type ExpectedByRaceNumber from \Turnmark\Scraper\Mikuni\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class BatchScraperTest extends TestCase
{
    /**
     * @param BatchArguments $arguments
     * @param ExpectedByRaceNumber $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(BatchScraperDataProvider::class, 'scrapeTimeProvider')]
    public function scrapeTime(array $arguments, array $expected): void
    {
        $this->assertSame($expected, BatchScraper::scrapeTime(...$arguments));
    }
}
