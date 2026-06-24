<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests;

/**
 * @psalm-type Date = \Carbon\CarbonInterface
 * @psalm-type StadiumNumber = int<1, 24>
 * @psalm-type StadiumNumbers = list<int<1, 24>>
 * @psalm-type Number = int<1, 12>
 * @psalm-type Numbers = list<int<1, 12>>
 * @psalm-type Arguments = array{Date, StadiumNumber, Number}
 * @psalm-type BatchArguments = array{Date, StadiumNumbers, Numbers}
 * @psalm-type Expected = non-empty-array<non-empty-string, mixed>
 * @psalm-type ExpectedByNumber = non-empty-array<Number, Expected>
 * @psalm-type ExpectedByStadiumNumber = non-empty-array<StadiumNumber, ExpectedByNumber>
 * @psalm-internal tests
 *
 * @author shimomo
 */
final class ScraperPsalmType
{
    //
}
