<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Factories;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Factories\HttpBrowserFactory;

/**
 * @author shimomo
 */
final class HttpBrowserFactoryTest extends TestCase
{
    /**
     * The lowest Chrome major the site was measured to answer without holding the request back.
     *
     * @var int
     */
    private const int MEASURED_TARPIT_FLOOR = 146;

    /**
     * @return void
     */
    #[Test]
    public function createClaimsTwoMajorsAheadOfTheAnchorOnTheAnchorDate(): void
    {
        $this->assertSame(153, $this->chromeMajorVersionOn('2026-07-28'));
    }

    /**
     * @return void
     */
    #[Test]
    public function createAdvancesOneMajorEveryFourWeeks(): void
    {
        $this->assertSame(153, $this->chromeMajorVersionOn('2026-08-24'));
        $this->assertSame(154, $this->chromeMajorVersionOn('2026-08-25'));
        $this->assertSame(155, $this->chromeMajorVersionOn('2026-10-02'));
        $this->assertSame(166, $this->chromeMajorVersionOn('2027-08-07'));
    }

    /**
     * @return void
     */
    #[Test]
    public function createNeverGoesBackwardsBeforeTheAnchor(): void
    {
        $this->assertSame(153, $this->chromeMajorVersionOn('2020-01-01'));
    }

    /**
     * @return void
     */
    #[Test]
    public function createStaysAboveTheMeasuredTarpitFloorForTheNextDecade(): void
    {
        $anchor = new DateTimeImmutable('2026-07-28');

        for ($days = 0; $days <= 3650; $days += 7) {
            $date = $anchor->modify('+' . $days . ' days')->format('Y-m-d');

            $this->assertGreaterThanOrEqual(
                self::MEASURED_TARPIT_FLOOR,
                $this->chromeMajorVersionOn($date),
                $date
            );
        }
    }

    /**
     * A user agent and client hint that name different majors are not something a real browser
     * sends.
     *
     * @return void
     */
    #[Test]
    public function createKeepsTheUserAgentAndClientHintOnTheSameVersion(): void
    {
        $httpBrowser = HttpBrowserFactory::create([], new DateTimeImmutable('2026-10-02'));

        $this->assertSame(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',
            $httpBrowser->getServerParameter('HTTP_USER_AGENT')
        );
        $this->assertSame(
            '"Google Chrome";v="155", "Chromium";v="155", "Not)A;Brand";v="24"',
            $httpBrowser->getServerParameter('HTTP_SEC_CH_UA')
        );
    }

    /**
     * @return void
     */
    #[Test]
    public function createReadsTodayWhenNoDateIsGiven(): void
    {
        $this->assertSame(
            HttpBrowserFactory::create([], new DateTimeImmutable())->getServerParameter('HTTP_USER_AGENT'),
            HttpBrowserFactory::create()->getServerParameter('HTTP_USER_AGENT')
        );
    }

    /**
     * @return void
     */
    #[Test]
    public function createLetsExtraParametersOverrideTheVersionedHeaders(): void
    {
        $httpBrowser = HttpBrowserFactory::create(['HTTP_USER_AGENT' => 'Turnmark/1.0']);

        $this->assertSame('Turnmark/1.0', $httpBrowser->getServerParameter('HTTP_USER_AGENT'));
    }

    /**
     * @param non-empty-string $date
     * @return int
     */
    private function chromeMajorVersionOn(string $date): int
    {
        $userAgent = (string) HttpBrowserFactory::create([], new DateTimeImmutable($date))
            ->getServerParameter('HTTP_USER_AGENT');

        $this->assertSame(1, preg_match('/Chrome\/(\d+)\./', $userAgent, $matches), $userAgent);

        return (int) $matches[1];
    }
}
