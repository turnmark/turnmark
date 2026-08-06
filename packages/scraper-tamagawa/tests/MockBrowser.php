<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tamagawa\Tests;

use RuntimeException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * Serves the pages saved under tests/fixtures in place of the live site, so that a test reads
 * the same bytes every time it runs. Which fixture answers a request is worked out from the URL
 * alone — the name of the page followed by the values of its query, as in
 * `oriten-20260628-12.html` — so a test only has to name the race it is about. A request
 * with no fixture behind it fails rather than quietly going out to the network.
 *
 * A fixture can also be named outright, which is how the pages that were edited by hand to hold
 * something the live site rarely serves are reached, since no URL leads to them.
 *
 * @author shimomo
 */
final class MockBrowser
{
    /**
     * @param ?non-empty-string $fixture
     * @return \Symfony\Component\BrowserKit\HttpBrowser
     */
    public static function create(?string $fixture = null): HttpBrowser
    {
        return new HttpBrowser(new MockHttpClient(
            static fn(string $method, string $url): MockResponse => self::respond($url, $fixture)
        ));
    }

    /**
     * @param string $url
     * @param ?non-empty-string $fixture
     * @return \Symfony\Component\HttpClient\Response\MockResponse
     * @throws \RuntimeException
     */
    private static function respond(string $url, ?string $fixture): MockResponse
    {
        $fixture ??= self::resolve($url);
        $path = __DIR__ . '/fixtures/' . $fixture;

        if (!is_file($path)) {
            throw new RuntimeException(
                sprintf('The fixture `%s` is missing for `%s`.', $fixture, $url)
            );
        }

        return new MockResponse((string) file_get_contents($path), [
            'response_headers' => ['content-type' => 'text/html; charset=utf-8'],
        ]);
    }

    /**
     * @param string $url
     * @return non-empty-string
     */
    private static function resolve(string $url): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $query = (string) parse_url($url, PHP_URL_QUERY);

        parse_str($query, $parameters);

        $values = array_map(fn(mixed $value): string => is_scalar($value) ? (string) $value : '', $parameters);

        return implode('-', [pathinfo($path, PATHINFO_FILENAME), ...array_values($values)]) . '.html';
    }
}
