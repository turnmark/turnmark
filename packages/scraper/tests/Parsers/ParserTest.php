<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests\Parsers;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Turnmark\Scraper\Parsers\Parser;

/**
 * @author shimomo
 */
final class ParserTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function parseNameKeepsTheFamilyAndGivenNameApart(): void
    {
        $this->assertSame(['name' => '丸野 一樹'], Parser::parseName('丸野 一樹'));
        $this->assertSame(['name' => '丸野 一樹'], Parser::parseName('丸野　　一樹'));
    }

    /**
     * A few names are printed without the space between the two parts, and those are listed so
     * that it can be put back.
     *
     * @return void
     */
    #[Test]
    public function parseNamePutsTheSpaceBackForAListedName(): void
    {
        $this->assertSame(['name' => 'マイケル 田代'], Parser::parseName('マイケル田代'));
        $this->assertSame(['name' => '小神野 紀代子'], Parser::parseName('小神野紀代子'));
    }

    /**
     * A name that is not listed keeps its printed form. Dropping it would lose the racer, and
     * there is no source key to fall back on.
     *
     * @return void
     */
    #[Test]
    public function parseNameKeepsAnUnlistedNameAsPrinted(): void
    {
        $this->assertSame(['name' => '山田太郎'], Parser::parseName('山田太郎'));
    }

    /**
     * @return void
     */
    #[Test]
    public function parseNameReportsAnEmptyValueAsMissing(): void
    {
        $this->assertSame(['name' => null], Parser::parseName(null));
        $this->assertSame(['name' => null], Parser::parseName(''));
    }
}
