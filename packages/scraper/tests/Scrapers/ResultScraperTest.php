<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Scrapers;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Scrapers\ResultScraper;
use Turnmark\Scraper\Tests\MockBrowser;

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
    public function scrape(array $arguments, array $expected): void
    {
        $this->assertSame($expected, ResultScraper::scrape(...$arguments, httpBrowser: MockBrowser::create()));
    }

    /**
     * A row whose combination reads but whose amount does not is kept, with the amount reported
     * as missing. Dropping the row would take the only sign that the page has moved with it.
     *
     * The fixture is the 2026-05-31 page with the amount of the trifecta blanked out; every
     * other row is untouched.
     *
     * @return void
     */
    #[Test]
    public function scrapeKeepsAPayoutRowWhoseAmountCannotBeRead(): void
    {
        $response = ResultScraper::scrape(
            '2026-05-31',
            6,
            12,
            MockBrowser::create('raceresult-missing-amount.html')
        );

        $payouts = $response['payouts'];

        $this->assertIsArray($payouts);
        $this->assertSame(
            [['combination' => '1-5-4', 'amount' => null, 'label' => null]],
            $payouts['trifecta']
        );
        $this->assertSame(
            [['combination' => '1=4=5', 'amount' => 1550, 'label' => null]],
            $payouts['trio']
        );
    }
}
