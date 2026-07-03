<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Scrapers;

use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Turnmark\Scraper\Contracts\Scraper;
use Turnmark\Scraper\Factories\HttpBrowserFactory;
use Turnmark\Scraper\Filters\Filter;
use Turnmark\Scraper\Filters\OddsFilter;
use Turnmark\Scraper\Scraper as BoatraceScraper;

/**
 * @author shimomo
 */
final class OddsScraper implements Scraper
{
    /**
     * @var non-empty-string
     */
    private static string $baseUrl = 'https://www.boatrace.jp';

    /**
     * @var non-empty-string
     */
    private static string $baseXPath = 'descendant-or-self::body/main/div/div/div';

    /**
     * @var int<0, 1>
     */
    private static int $baseLevel = 0;

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    #[\Override]
    public static function scrape(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null,
    ): array {
        $date = Carbon::parse($date);

        $response = [];

        $response += self::scrapeTrifecta($date, $stadiumNumber, $raceNumber, $httpBrowser);
        BoatraceScraper::throttle();
        $response += self::scrapeTrio($date, $stadiumNumber, $raceNumber, $httpBrowser);
        BoatraceScraper::throttle();
        $response += self::scrapeExactaAndQuinella($date, $stadiumNumber, $raceNumber, $httpBrowser);
        BoatraceScraper::throttle();
        $response += self::scrapeQuinellaPlace($date, $stadiumNumber, $raceNumber, $httpBrowser);
        BoatraceScraper::throttle();
        $response += self::scrapeWinAndPlace($date, $stadiumNumber, $raceNumber, $httpBrowser);

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeTrifecta(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/odds3t?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = $stadiumNumber;
        $response['race_number'] = $raceNumber;

        $trifecta123XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta124XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta125XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta126XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta132XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta134XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta135XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta136XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta142XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta143XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta145XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[11]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta146XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[12]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta152XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[13]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta153XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[14]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta154XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[15]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta156XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[16]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta162XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[17]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta163XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[18]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta164XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[19]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta165XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[20]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta213XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta214XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta215XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta216XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta231XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta234XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta235XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta236XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta241XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta243XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta245XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[11]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta246XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[12]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta251XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[13]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta253XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[14]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta254XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[15]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta256XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[16]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta261XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[17]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta263XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[18]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta264XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[19]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta265XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[20]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta312XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[9]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta314XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta315XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta316XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta321XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[9]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta324XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta325XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta326XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta341XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[9]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta342XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta345XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[11]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta346XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[12]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta351XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[13]/td[9]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta352XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[14]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta354XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[15]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta356XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[16]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta361XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[17]/td[9]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta362XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[18]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta364XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[19]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta365XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[20]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta412XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta413XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta415XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta416XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta421XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta423XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta425XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta426XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta431XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta432XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta435XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[11]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta436XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[12]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta451XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[13]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta452XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[14]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta453XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[15]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta456XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[16]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta461XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[17]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta462XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[18]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta463XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[19]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta465XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[20]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta512XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[15]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta513XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta514XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta516XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta521XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[15]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta523XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta524XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta526XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta531XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[15]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta532XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta534XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[11]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta536XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[12]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta541XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[13]/td[15]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta542XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[14]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta543XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[15]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta546XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[16]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta561XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[17]/td[15]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta562XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[18]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta563XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[19]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta564XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[20]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta612XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[18]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta613XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta614XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta615XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta621XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[18]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta623XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta624XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta625XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta631XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[18]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta632XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta634XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[11]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta635XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[12]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta641XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[13]/td[18]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta642XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[14]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta643XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[15]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta645XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[16]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta651XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[17]/td[18]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta652XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[18]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta653XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[19]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $trifecta654XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[20]/td[12]', self::$baseXPath, self::$baseLevel + 7);

        $response['trifecta'][1][2][3] = OddsFilter::byXPath($scraper, $trifecta123XPath);
        $response['trifecta'][1][2][4] = OddsFilter::byXPath($scraper, $trifecta124XPath);
        $response['trifecta'][1][2][5] = OddsFilter::byXPath($scraper, $trifecta125XPath);
        $response['trifecta'][1][2][6] = OddsFilter::byXPath($scraper, $trifecta126XPath);
        $response['trifecta'][1][3][2] = OddsFilter::byXPath($scraper, $trifecta132XPath);
        $response['trifecta'][1][3][4] = OddsFilter::byXPath($scraper, $trifecta134XPath);
        $response['trifecta'][1][3][5] = OddsFilter::byXPath($scraper, $trifecta135XPath);
        $response['trifecta'][1][3][6] = OddsFilter::byXPath($scraper, $trifecta136XPath);
        $response['trifecta'][1][4][2] = OddsFilter::byXPath($scraper, $trifecta142XPath);
        $response['trifecta'][1][4][3] = OddsFilter::byXPath($scraper, $trifecta143XPath);
        $response['trifecta'][1][4][5] = OddsFilter::byXPath($scraper, $trifecta145XPath);
        $response['trifecta'][1][4][6] = OddsFilter::byXPath($scraper, $trifecta146XPath);
        $response['trifecta'][1][5][2] = OddsFilter::byXPath($scraper, $trifecta152XPath);
        $response['trifecta'][1][5][3] = OddsFilter::byXPath($scraper, $trifecta153XPath);
        $response['trifecta'][1][5][4] = OddsFilter::byXPath($scraper, $trifecta154XPath);
        $response['trifecta'][1][5][6] = OddsFilter::byXPath($scraper, $trifecta156XPath);
        $response['trifecta'][1][6][2] = OddsFilter::byXPath($scraper, $trifecta162XPath);
        $response['trifecta'][1][6][3] = OddsFilter::byXPath($scraper, $trifecta163XPath);
        $response['trifecta'][1][6][4] = OddsFilter::byXPath($scraper, $trifecta164XPath);
        $response['trifecta'][1][6][5] = OddsFilter::byXPath($scraper, $trifecta165XPath);
        $response['trifecta'][2][1][3] = OddsFilter::byXPath($scraper, $trifecta213XPath);
        $response['trifecta'][2][1][4] = OddsFilter::byXPath($scraper, $trifecta214XPath);
        $response['trifecta'][2][1][5] = OddsFilter::byXPath($scraper, $trifecta215XPath);
        $response['trifecta'][2][1][6] = OddsFilter::byXPath($scraper, $trifecta216XPath);
        $response['trifecta'][2][3][1] = OddsFilter::byXPath($scraper, $trifecta231XPath);
        $response['trifecta'][2][3][4] = OddsFilter::byXPath($scraper, $trifecta234XPath);
        $response['trifecta'][2][3][5] = OddsFilter::byXPath($scraper, $trifecta235XPath);
        $response['trifecta'][2][3][6] = OddsFilter::byXPath($scraper, $trifecta236XPath);
        $response['trifecta'][2][4][1] = OddsFilter::byXPath($scraper, $trifecta241XPath);
        $response['trifecta'][2][4][3] = OddsFilter::byXPath($scraper, $trifecta243XPath);
        $response['trifecta'][2][4][5] = OddsFilter::byXPath($scraper, $trifecta245XPath);
        $response['trifecta'][2][4][6] = OddsFilter::byXPath($scraper, $trifecta246XPath);
        $response['trifecta'][2][5][1] = OddsFilter::byXPath($scraper, $trifecta251XPath);
        $response['trifecta'][2][5][3] = OddsFilter::byXPath($scraper, $trifecta253XPath);
        $response['trifecta'][2][5][4] = OddsFilter::byXPath($scraper, $trifecta254XPath);
        $response['trifecta'][2][5][6] = OddsFilter::byXPath($scraper, $trifecta256XPath);
        $response['trifecta'][2][6][1] = OddsFilter::byXPath($scraper, $trifecta261XPath);
        $response['trifecta'][2][6][3] = OddsFilter::byXPath($scraper, $trifecta263XPath);
        $response['trifecta'][2][6][4] = OddsFilter::byXPath($scraper, $trifecta264XPath);
        $response['trifecta'][2][6][5] = OddsFilter::byXPath($scraper, $trifecta265XPath);
        $response['trifecta'][3][1][2] = OddsFilter::byXPath($scraper, $trifecta312XPath);
        $response['trifecta'][3][1][4] = OddsFilter::byXPath($scraper, $trifecta314XPath);
        $response['trifecta'][3][1][5] = OddsFilter::byXPath($scraper, $trifecta315XPath);
        $response['trifecta'][3][1][6] = OddsFilter::byXPath($scraper, $trifecta316XPath);
        $response['trifecta'][3][2][1] = OddsFilter::byXPath($scraper, $trifecta321XPath);
        $response['trifecta'][3][2][4] = OddsFilter::byXPath($scraper, $trifecta324XPath);
        $response['trifecta'][3][2][5] = OddsFilter::byXPath($scraper, $trifecta325XPath);
        $response['trifecta'][3][2][6] = OddsFilter::byXPath($scraper, $trifecta326XPath);
        $response['trifecta'][3][4][1] = OddsFilter::byXPath($scraper, $trifecta341XPath);
        $response['trifecta'][3][4][2] = OddsFilter::byXPath($scraper, $trifecta342XPath);
        $response['trifecta'][3][4][5] = OddsFilter::byXPath($scraper, $trifecta345XPath);
        $response['trifecta'][3][4][6] = OddsFilter::byXPath($scraper, $trifecta346XPath);
        $response['trifecta'][3][5][1] = OddsFilter::byXPath($scraper, $trifecta351XPath);
        $response['trifecta'][3][5][2] = OddsFilter::byXPath($scraper, $trifecta352XPath);
        $response['trifecta'][3][5][4] = OddsFilter::byXPath($scraper, $trifecta354XPath);
        $response['trifecta'][3][5][6] = OddsFilter::byXPath($scraper, $trifecta356XPath);
        $response['trifecta'][3][6][1] = OddsFilter::byXPath($scraper, $trifecta361XPath);
        $response['trifecta'][3][6][2] = OddsFilter::byXPath($scraper, $trifecta362XPath);
        $response['trifecta'][3][6][4] = OddsFilter::byXPath($scraper, $trifecta364XPath);
        $response['trifecta'][3][6][5] = OddsFilter::byXPath($scraper, $trifecta365XPath);
        $response['trifecta'][4][1][2] = OddsFilter::byXPath($scraper, $trifecta412XPath);
        $response['trifecta'][4][1][3] = OddsFilter::byXPath($scraper, $trifecta413XPath);
        $response['trifecta'][4][1][5] = OddsFilter::byXPath($scraper, $trifecta415XPath);
        $response['trifecta'][4][1][6] = OddsFilter::byXPath($scraper, $trifecta416XPath);
        $response['trifecta'][4][2][1] = OddsFilter::byXPath($scraper, $trifecta421XPath);
        $response['trifecta'][4][2][3] = OddsFilter::byXPath($scraper, $trifecta423XPath);
        $response['trifecta'][4][2][5] = OddsFilter::byXPath($scraper, $trifecta425XPath);
        $response['trifecta'][4][2][6] = OddsFilter::byXPath($scraper, $trifecta426XPath);
        $response['trifecta'][4][3][1] = OddsFilter::byXPath($scraper, $trifecta431XPath);
        $response['trifecta'][4][3][2] = OddsFilter::byXPath($scraper, $trifecta432XPath);
        $response['trifecta'][4][3][5] = OddsFilter::byXPath($scraper, $trifecta435XPath);
        $response['trifecta'][4][3][6] = OddsFilter::byXPath($scraper, $trifecta436XPath);
        $response['trifecta'][4][5][1] = OddsFilter::byXPath($scraper, $trifecta451XPath);
        $response['trifecta'][4][5][2] = OddsFilter::byXPath($scraper, $trifecta452XPath);
        $response['trifecta'][4][5][3] = OddsFilter::byXPath($scraper, $trifecta453XPath);
        $response['trifecta'][4][5][6] = OddsFilter::byXPath($scraper, $trifecta456XPath);
        $response['trifecta'][4][6][1] = OddsFilter::byXPath($scraper, $trifecta461XPath);
        $response['trifecta'][4][6][2] = OddsFilter::byXPath($scraper, $trifecta462XPath);
        $response['trifecta'][4][6][3] = OddsFilter::byXPath($scraper, $trifecta463XPath);
        $response['trifecta'][4][6][5] = OddsFilter::byXPath($scraper, $trifecta465XPath);
        $response['trifecta'][5][1][2] = OddsFilter::byXPath($scraper, $trifecta512XPath);
        $response['trifecta'][5][1][3] = OddsFilter::byXPath($scraper, $trifecta513XPath);
        $response['trifecta'][5][1][4] = OddsFilter::byXPath($scraper, $trifecta514XPath);
        $response['trifecta'][5][1][6] = OddsFilter::byXPath($scraper, $trifecta516XPath);
        $response['trifecta'][5][2][1] = OddsFilter::byXPath($scraper, $trifecta521XPath);
        $response['trifecta'][5][2][3] = OddsFilter::byXPath($scraper, $trifecta523XPath);
        $response['trifecta'][5][2][4] = OddsFilter::byXPath($scraper, $trifecta524XPath);
        $response['trifecta'][5][2][6] = OddsFilter::byXPath($scraper, $trifecta526XPath);
        $response['trifecta'][5][3][1] = OddsFilter::byXPath($scraper, $trifecta531XPath);
        $response['trifecta'][5][3][2] = OddsFilter::byXPath($scraper, $trifecta532XPath);
        $response['trifecta'][5][3][4] = OddsFilter::byXPath($scraper, $trifecta534XPath);
        $response['trifecta'][5][3][6] = OddsFilter::byXPath($scraper, $trifecta536XPath);
        $response['trifecta'][5][4][1] = OddsFilter::byXPath($scraper, $trifecta541XPath);
        $response['trifecta'][5][4][2] = OddsFilter::byXPath($scraper, $trifecta542XPath);
        $response['trifecta'][5][4][3] = OddsFilter::byXPath($scraper, $trifecta543XPath);
        $response['trifecta'][5][4][6] = OddsFilter::byXPath($scraper, $trifecta546XPath);
        $response['trifecta'][5][6][1] = OddsFilter::byXPath($scraper, $trifecta561XPath);
        $response['trifecta'][5][6][2] = OddsFilter::byXPath($scraper, $trifecta562XPath);
        $response['trifecta'][5][6][3] = OddsFilter::byXPath($scraper, $trifecta563XPath);
        $response['trifecta'][5][6][4] = OddsFilter::byXPath($scraper, $trifecta564XPath);
        $response['trifecta'][6][1][2] = OddsFilter::byXPath($scraper, $trifecta612XPath);
        $response['trifecta'][6][1][3] = OddsFilter::byXPath($scraper, $trifecta613XPath);
        $response['trifecta'][6][1][4] = OddsFilter::byXPath($scraper, $trifecta614XPath);
        $response['trifecta'][6][1][5] = OddsFilter::byXPath($scraper, $trifecta615XPath);
        $response['trifecta'][6][2][1] = OddsFilter::byXPath($scraper, $trifecta621XPath);
        $response['trifecta'][6][2][3] = OddsFilter::byXPath($scraper, $trifecta623XPath);
        $response['trifecta'][6][2][4] = OddsFilter::byXPath($scraper, $trifecta624XPath);
        $response['trifecta'][6][2][5] = OddsFilter::byXPath($scraper, $trifecta625XPath);
        $response['trifecta'][6][3][1] = OddsFilter::byXPath($scraper, $trifecta631XPath);
        $response['trifecta'][6][3][2] = OddsFilter::byXPath($scraper, $trifecta632XPath);
        $response['trifecta'][6][3][4] = OddsFilter::byXPath($scraper, $trifecta634XPath);
        $response['trifecta'][6][3][5] = OddsFilter::byXPath($scraper, $trifecta635XPath);
        $response['trifecta'][6][4][1] = OddsFilter::byXPath($scraper, $trifecta641XPath);
        $response['trifecta'][6][4][2] = OddsFilter::byXPath($scraper, $trifecta642XPath);
        $response['trifecta'][6][4][3] = OddsFilter::byXPath($scraper, $trifecta643XPath);
        $response['trifecta'][6][4][5] = OddsFilter::byXPath($scraper, $trifecta645XPath);
        $response['trifecta'][6][5][1] = OddsFilter::byXPath($scraper, $trifecta651XPath);
        $response['trifecta'][6][5][2] = OddsFilter::byXPath($scraper, $trifecta652XPath);
        $response['trifecta'][6][5][3] = OddsFilter::byXPath($scraper, $trifecta653XPath);
        $response['trifecta'][6][5][4] = OddsFilter::byXPath($scraper, $trifecta654XPath);

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeTrio(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/odds3f?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = $stadiumNumber;
        $response['race_number'] = $raceNumber;

        $trio123XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trio124XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trio125XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trio126XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trio134XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trio135XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trio136XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trio145XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trio146XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $trio156XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[3]', self::$baseXPath, self::$baseLevel + 7);
        $trio234XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trio235XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[6]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trio236XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[7]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trio245XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trio246XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $trio256XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trio345XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[8]/td[9]', self::$baseXPath, self::$baseLevel + 7);
        $trio346XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[9]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $trio356XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[9]', self::$baseXPath, self::$baseLevel + 7);
        $trio456XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[10]/td[12]', self::$baseXPath, self::$baseLevel + 7);

        $response['trio'][1][2][3] = OddsFilter::byXPath($scraper, $trio123XPath);
        $response['trio'][1][2][4] = OddsFilter::byXPath($scraper, $trio124XPath);
        $response['trio'][1][2][5] = OddsFilter::byXPath($scraper, $trio125XPath);
        $response['trio'][1][2][6] = OddsFilter::byXPath($scraper, $trio126XPath);
        $response['trio'][1][3][4] = OddsFilter::byXPath($scraper, $trio134XPath);
        $response['trio'][1][3][5] = OddsFilter::byXPath($scraper, $trio135XPath);
        $response['trio'][1][3][6] = OddsFilter::byXPath($scraper, $trio136XPath);
        $response['trio'][1][4][5] = OddsFilter::byXPath($scraper, $trio145XPath);
        $response['trio'][1][4][6] = OddsFilter::byXPath($scraper, $trio146XPath);
        $response['trio'][1][5][6] = OddsFilter::byXPath($scraper, $trio156XPath);
        $response['trio'][2][3][4] = OddsFilter::byXPath($scraper, $trio234XPath);
        $response['trio'][2][3][5] = OddsFilter::byXPath($scraper, $trio235XPath);
        $response['trio'][2][3][6] = OddsFilter::byXPath($scraper, $trio236XPath);
        $response['trio'][2][4][5] = OddsFilter::byXPath($scraper, $trio245XPath);
        $response['trio'][2][4][6] = OddsFilter::byXPath($scraper, $trio246XPath);
        $response['trio'][2][5][6] = OddsFilter::byXPath($scraper, $trio256XPath);
        $response['trio'][3][4][5] = OddsFilter::byXPath($scraper, $trio345XPath);
        $response['trio'][3][4][6] = OddsFilter::byXPath($scraper, $trio346XPath);
        $response['trio'][3][5][6] = OddsFilter::byXPath($scraper, $trio356XPath);
        $response['trio'][4][5][6] = OddsFilter::byXPath($scraper, $trio456XPath);

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeExactaAndQuinella(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/odds2tf?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = $stadiumNumber;
        $response['race_number'] = $raceNumber;

        $exacta12XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $exacta13XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $exacta14XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $exacta15XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $exacta16XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $exacta21XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $exacta23XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $exacta24XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $exacta25XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $exacta26XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $exacta31XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $exacta32XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $exacta34XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $exacta35XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $exacta36XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $exacta41XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $exacta42XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $exacta43XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $exacta45XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $exacta46XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $exacta51XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $exacta52XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $exacta53XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $exacta54XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $exacta56XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[10]', self::$baseXPath, self::$baseLevel + 7);
        $exacta61XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $exacta62XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $exacta63XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $exacta64XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[12]', self::$baseXPath, self::$baseLevel + 7);
        $exacta65XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[12]', self::$baseXPath, self::$baseLevel + 7);

        $response['exacta'][1][2] = OddsFilter::byXPath($scraper, $exacta12XPath);
        $response['exacta'][1][3] = OddsFilter::byXPath($scraper, $exacta13XPath);
        $response['exacta'][1][4] = OddsFilter::byXPath($scraper, $exacta14XPath);
        $response['exacta'][1][5] = OddsFilter::byXPath($scraper, $exacta15XPath);
        $response['exacta'][1][6] = OddsFilter::byXPath($scraper, $exacta16XPath);
        $response['exacta'][2][1] = OddsFilter::byXPath($scraper, $exacta21XPath);
        $response['exacta'][2][3] = OddsFilter::byXPath($scraper, $exacta23XPath);
        $response['exacta'][2][4] = OddsFilter::byXPath($scraper, $exacta24XPath);
        $response['exacta'][2][5] = OddsFilter::byXPath($scraper, $exacta25XPath);
        $response['exacta'][2][6] = OddsFilter::byXPath($scraper, $exacta26XPath);
        $response['exacta'][3][1] = OddsFilter::byXPath($scraper, $exacta31XPath);
        $response['exacta'][3][2] = OddsFilter::byXPath($scraper, $exacta32XPath);
        $response['exacta'][3][4] = OddsFilter::byXPath($scraper, $exacta34XPath);
        $response['exacta'][3][5] = OddsFilter::byXPath($scraper, $exacta35XPath);
        $response['exacta'][3][6] = OddsFilter::byXPath($scraper, $exacta36XPath);
        $response['exacta'][4][1] = OddsFilter::byXPath($scraper, $exacta41XPath);
        $response['exacta'][4][2] = OddsFilter::byXPath($scraper, $exacta42XPath);
        $response['exacta'][4][3] = OddsFilter::byXPath($scraper, $exacta43XPath);
        $response['exacta'][4][5] = OddsFilter::byXPath($scraper, $exacta45XPath);
        $response['exacta'][4][6] = OddsFilter::byXPath($scraper, $exacta46XPath);
        $response['exacta'][5][1] = OddsFilter::byXPath($scraper, $exacta51XPath);
        $response['exacta'][5][2] = OddsFilter::byXPath($scraper, $exacta52XPath);
        $response['exacta'][5][3] = OddsFilter::byXPath($scraper, $exacta53XPath);
        $response['exacta'][5][4] = OddsFilter::byXPath($scraper, $exacta54XPath);
        $response['exacta'][5][6] = OddsFilter::byXPath($scraper, $exacta56XPath);
        $response['exacta'][6][1] = OddsFilter::byXPath($scraper, $exacta61XPath);
        $response['exacta'][6][2] = OddsFilter::byXPath($scraper, $exacta62XPath);
        $response['exacta'][6][3] = OddsFilter::byXPath($scraper, $exacta63XPath);
        $response['exacta'][6][4] = OddsFilter::byXPath($scraper, $exacta64XPath);
        $response['exacta'][6][5] = OddsFilter::byXPath($scraper, $exacta65XPath);

        $quinella12XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[2]', self::$baseXPath, self::$baseLevel + 9);
        $quinella13XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[2]', self::$baseXPath, self::$baseLevel + 9);
        $quinella14XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[2]', self::$baseXPath, self::$baseLevel + 9);
        $quinella15XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[2]', self::$baseXPath, self::$baseLevel + 9);
        $quinella16XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[2]', self::$baseXPath, self::$baseLevel + 9);
        $quinella23XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[4]', self::$baseXPath, self::$baseLevel + 9);
        $quinella24XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[4]', self::$baseXPath, self::$baseLevel + 9);
        $quinella25XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[4]', self::$baseXPath, self::$baseLevel + 9);
        $quinella26XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[4]', self::$baseXPath, self::$baseLevel + 9);
        $quinella34XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[6]', self::$baseXPath, self::$baseLevel + 9);
        $quinella35XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[6]', self::$baseXPath, self::$baseLevel + 9);
        $quinella36XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[6]', self::$baseXPath, self::$baseLevel + 9);
        $quinella45XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[8]', self::$baseXPath, self::$baseLevel + 9);
        $quinella46XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[8]', self::$baseXPath, self::$baseLevel + 9);
        $quinella56XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[10]', self::$baseXPath, self::$baseLevel + 9);

        $response['quinella'][1][2] = OddsFilter::byXPath($scraper, $quinella12XPath);
        $response['quinella'][1][3] = OddsFilter::byXPath($scraper, $quinella13XPath);
        $response['quinella'][1][4] = OddsFilter::byXPath($scraper, $quinella14XPath);
        $response['quinella'][1][5] = OddsFilter::byXPath($scraper, $quinella15XPath);
        $response['quinella'][1][6] = OddsFilter::byXPath($scraper, $quinella16XPath);
        $response['quinella'][2][3] = OddsFilter::byXPath($scraper, $quinella23XPath);
        $response['quinella'][2][4] = OddsFilter::byXPath($scraper, $quinella24XPath);
        $response['quinella'][2][5] = OddsFilter::byXPath($scraper, $quinella25XPath);
        $response['quinella'][2][6] = OddsFilter::byXPath($scraper, $quinella26XPath);
        $response['quinella'][3][4] = OddsFilter::byXPath($scraper, $quinella34XPath);
        $response['quinella'][3][5] = OddsFilter::byXPath($scraper, $quinella35XPath);
        $response['quinella'][3][6] = OddsFilter::byXPath($scraper, $quinella36XPath);
        $response['quinella'][4][5] = OddsFilter::byXPath($scraper, $quinella45XPath);
        $response['quinella'][4][6] = OddsFilter::byXPath($scraper, $quinella46XPath);
        $response['quinella'][5][6] = OddsFilter::byXPath($scraper, $quinella56XPath);

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeExacta(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        return self::scrapeExactaAndQuinella($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeQuinella(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        return self::scrapeExactaAndQuinella($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeQuinellaPlace(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/oddsk?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = $stadiumNumber;
        $response['race_number'] = $raceNumber;

        $quinellaPlace12XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[1]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace13XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace14XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace15XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace16XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[2]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace23XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[2]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace24XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace25XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace26XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[4]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace34XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[3]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace35XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace36XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[6]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace45XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[4]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace46XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[8]', self::$baseXPath, self::$baseLevel + 7);
        $quinellaPlace56XPath = sprintf('%s/div[2]/div[%d]/table/tbody/tr[5]/td[10]', self::$baseXPath, self::$baseLevel + 7);

        $response['quinella_place'][1][2] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace12XPath);
        $response['quinella_place'][1][3] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace13XPath);
        $response['quinella_place'][1][4] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace14XPath);
        $response['quinella_place'][1][5] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace15XPath);
        $response['quinella_place'][1][6] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace16XPath);
        $response['quinella_place'][2][3] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace23XPath);
        $response['quinella_place'][2][4] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace24XPath);
        $response['quinella_place'][2][5] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace25XPath);
        $response['quinella_place'][2][6] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace26XPath);
        $response['quinella_place'][3][4] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace34XPath);
        $response['quinella_place'][3][5] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace35XPath);
        $response['quinella_place'][3][6] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace36XPath);
        $response['quinella_place'][4][5] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace45XPath);
        $response['quinella_place'][4][6] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace46XPath);
        $response['quinella_place'][5][6] = OddsFilter::byXPathAsRange($scraper, $quinellaPlace56XPath);

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeWinAndPlace(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        $date = Carbon::parse($date);

        $scraperFormat = '%s/owpc/pc/race/oddstf?hd=%s&jcd=%02d&rno=%d';
        $scraperUrl = sprintf($scraperFormat, self::$baseUrl, $date->format('Ymd'), $stadiumNumber, $raceNumber);
        $scraper = ($httpBrowser ?? HttpBrowserFactory::create())->request('GET', $scraperUrl);

        $levelFormat = '%s/div[2]/div[3]/ul/li';
        $levelXPath = sprintf($levelFormat, self::$baseXPath);

        self::$baseLevel = 0;
        if (Filter::byXPath($scraper, $levelXPath) !== null) {
            self::$baseLevel = 1;
        }

        $response = [];

        $response['date'] = $date->format('Y-m-d');
        $response['stadium_number'] = $stadiumNumber;
        $response['race_number'] = $raceNumber;

        $win1XPath = sprintf('%s/div[2]/div[%d]/div[1]/div[2]/table/tbody[1]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $win2XPath = sprintf('%s/div[2]/div[%d]/div[1]/div[2]/table/tbody[2]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $win3XPath = sprintf('%s/div[2]/div[%d]/div[1]/div[2]/table/tbody[3]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $win4XPath = sprintf('%s/div[2]/div[%d]/div[1]/div[2]/table/tbody[4]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $win5XPath = sprintf('%s/div[2]/div[%d]/div[1]/div[2]/table/tbody[5]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $win6XPath = sprintf('%s/div[2]/div[%d]/div[1]/div[2]/table/tbody[6]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);

        $response['win'][1] = OddsFilter::byXPath($scraper, $win1XPath);
        $response['win'][2] = OddsFilter::byXPath($scraper, $win2XPath);
        $response['win'][3] = OddsFilter::byXPath($scraper, $win3XPath);
        $response['win'][4] = OddsFilter::byXPath($scraper, $win4XPath);
        $response['win'][5] = OddsFilter::byXPath($scraper, $win5XPath);
        $response['win'][6] = OddsFilter::byXPath($scraper, $win6XPath);

        $place1XPath = sprintf('%s/div[2]/div[%d]/div[2]/div[2]/table/tbody[1]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $place2XPath = sprintf('%s/div[2]/div[%d]/div[2]/div[2]/table/tbody[2]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $place3XPath = sprintf('%s/div[2]/div[%d]/div[2]/div[2]/table/tbody[3]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $place4XPath = sprintf('%s/div[2]/div[%d]/div[2]/div[2]/table/tbody[4]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $place5XPath = sprintf('%s/div[2]/div[%d]/div[2]/div[2]/table/tbody[5]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);
        $place6XPath = sprintf('%s/div[2]/div[%d]/div[2]/div[2]/table/tbody[6]/tr/td[3]', self::$baseXPath, self::$baseLevel + 6);

        $response['place'][1] = OddsFilter::byXPathAsRange($scraper, $place1XPath);
        $response['place'][2] = OddsFilter::byXPathAsRange($scraper, $place2XPath);
        $response['place'][3] = OddsFilter::byXPathAsRange($scraper, $place3XPath);
        $response['place'][4] = OddsFilter::byXPathAsRange($scraper, $place4XPath);
        $response['place'][5] = OddsFilter::byXPathAsRange($scraper, $place5XPath);
        $response['place'][6] = OddsFilter::byXPathAsRange($scraper, $place6XPath);

        return $response;
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapeWin(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        return self::scrapeWinAndPlace($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }

    /**
     * @param \DateTimeInterface|non-empty-string $date
     * @param int<1, 24> $stadiumNumber
     * @param int<1, 12> $raceNumber
     * @param ?\Symfony\Component\BrowserKit\HttpBrowser $httpBrowser
     * @return array<non-empty-string, mixed>
     */
    public static function scrapePlace(
        DateTimeInterface|string $date,
        int $stadiumNumber,
        int $raceNumber,
        ?HttpBrowser $httpBrowser = null
    ): array {
        return self::scrapeWinAndPlace($date, $stadiumNumber, $raceNumber, $httpBrowser);
    }
}
