<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Scraper;

/**
 * @psalm-import-type Date from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type StadiumNumber from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type Arguments from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type BatchArguments from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type Expected from \Turnmark\Scraper\Tests\ScraperPsalmType
 * @psalm-import-type ExpectedByStadiumNumber from \Turnmark\Scraper\Tests\ScraperPsalmType
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
    #[DataProviderExternal(ScraperDataProvider::class, 'scrapeProgramProvider')]
    public function scrapeProgram(array $arguments, array $expected): void
    {
        $this->assertSame($expected, Scraper::scrapeProgram(...$arguments));
    }

    /**
     * @param BatchArguments $arguments
     * @param ExpectedByStadiumNumber $expected
     * @return void
     */
    #[Test]
    #[DataProviderExternal(ScraperDataProvider::class, 'scrapeProgramBulkProvider')]
    public function scrapeProgramBulk(array $arguments, array $expected): void
    {
        $this->assertSame($expected, Scraper::scrapeProgramBulk(...$arguments));
    }
}
