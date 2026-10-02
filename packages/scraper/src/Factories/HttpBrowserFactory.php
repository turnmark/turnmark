<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Factories;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Symfony\Component\BrowserKit\HttpBrowser;

/**
 * Builds the browser every scraper requests through, carrying the headers a desktop browser
 * would send.
 *
 * The site holds back a request that claims a Chrome older than its floor, and that floor rises
 * with Chrome's release calendar, so a pinned version falls behind. The version is extrapolated
 * from the date instead: one major every four weeks from a known release, plus a lead, because
 * claiming a newer version costs nothing while an older one slows every request.
 *
 * @author shimomo
 */
final class HttpBrowserFactory
{
    /**
     * @var int
     */
    private const int ANCHOR_MAJOR = 151;

    /**
     * @var non-empty-string
     */
    private const string ANCHOR_DATE = '2026-07-28';

    /**
     * @var int
     */
    private const int RELEASE_INTERVAL_DAYS = 28;

    /**
     * @var int
     */
    private const int LEAD_MAJORS = 2;

    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private const array VERSIONED_SERVER_PARAMETERS = [
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' .
            '(KHTML, like Gecko) Chrome/%1$d.0.0.0 Safari/537.36',
        'HTTP_SEC_CH_UA' => '"Google Chrome";v="%1$d", "Chromium";v="%1$d", "Not)A;Brand";v="24"',
    ];

    /**
     * @param array<non-empty-string, non-empty-string> $extraParameters
     * @param ?\DateTimeInterface $now
     * @return \Symfony\Component\BrowserKit\HttpBrowser
     */
    public static function create(array $extraParameters = [], ?DateTimeInterface $now = null): HttpBrowser
    {
        $httpBrowser = new HttpBrowser();

        $httpBrowser->setServerParameters(array_merge(self::versionedServerParameters($now), [
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'HTTP_ACCEPT_LANGUAGE' => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_SEC_CH_UA_PLATFORM' => '"Windows"',
            'HTTP_SEC_CH_UA_MOBILE' => '?0',
            'HTTP_SEC_FETCH_SITE' => 'none',
            'HTTP_SEC_FETCH_MODE' => 'navigate',
            'HTTP_SEC_FETCH_USER' => '?1',
            'HTTP_SEC_FETCH_DEST' => 'document',
            'HTTP_PRIORITY' => 'u=0, i',
        ], $extraParameters));

        return $httpBrowser;
    }

    /**
     * @param ?\DateTimeInterface $now
     * @return array<non-empty-string, non-empty-string>
     */
    private static function versionedServerParameters(?DateTimeInterface $now): array
    {
        $chromeMajorVersion = self::chromeMajorVersion($now);

        return array_map(
            /**
             * @param non-empty-string $template
             * @return non-empty-string
             */
            static fn(string $template): string => sprintf($template, $chromeMajorVersion),
            self::VERSIONED_SERVER_PARAMETERS
        );
    }

    /**
     * @param ?\DateTimeInterface $now
     * @return int
     */
    private static function chromeMajorVersion(?DateTimeInterface $now): int
    {
        $timeZone = new DateTimeZone('UTC');
        $anchor = new DateTimeImmutable(self::ANCHOR_DATE, $timeZone);
        $today = new DateTimeImmutable(($now ?? new DateTimeImmutable())->format('Y-m-d'), $timeZone);

        $elapsedDays = $today > $anchor ? (int) $anchor->diff($today)->days : 0;

        return self::ANCHOR_MAJOR
            + intdiv($elapsedDays, self::RELEASE_INTERVAL_DAYS)
            + self::LEAD_MAJORS;
    }
}
