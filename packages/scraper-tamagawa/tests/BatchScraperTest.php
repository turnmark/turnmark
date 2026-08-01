<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tamagawa\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Tamagawa\BatchScraper;

/**
 * @psalm-import-type BatchArguments from \Turnmark\Scraper\Tamagawa\Tests\ScraperPsalmType
 * @psalm-import-type ExpectedByRaceNumber from \Turnmark\Scraper\Tamagawa\Tests\ScraperPsalmType
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
