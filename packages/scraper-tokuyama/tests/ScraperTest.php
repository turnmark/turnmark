<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tokuyama\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Tokuyama\Scraper;

/**
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 * @psalm-import-type BatchArguments from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 * @psalm-import-type ExpectedByRaceNumber from \Turnmark\Scraper\Tokuyama\Tests\ScraperPsalmType
 *
 * @author shimomo
 */
final class ScraperTest extends TestCase
{
    /**
     * @param Arguments $arguments
     * @param Expected $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(ScraperDataProvider::class, 'scrapeTimeProvider')]
    public function scrapeTime(array $arguments, array $expected): void
    {
        $this->assertSame($expected, Scraper::scrapeTime(...$arguments));
    }

    /**
     * @param BatchArguments $arguments
     * @param ExpectedByRaceNumber $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(ScraperDataProvider::class, 'scrapeTimeBulkProvider')]
    public function scrapeTimeBulk(array $arguments, array $expected): void
    {
        $this->assertSame($expected, Scraper::scrapeTimeBulk(...$arguments));
    }
}
