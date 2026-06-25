<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Tests;

/**
 * @psalm-type Date = \Carbon\CarbonInterface
 * @psalm-type StadiumNumber = int<1, 24>
 * @psalm-type StadiumNumbers = list<int<1, 24>>
 * @psalm-type RaceNumber = int<1, 12>
 * @psalm-type RaceNumbers = list<int<1, 12>>
 * @psalm-type Arguments = array{Date, StadiumNumber, RaceNumber}
 * @psalm-type BatchArguments = array{Date, StadiumNumbers, RaceNumbers}
 * @psalm-type Expected = non-empty-array<non-empty-string, mixed>
 * @psalm-type ExpectedByRaceNumber = non-empty-array<RaceNumber, Expected>
 * @psalm-type ExpectedByStadiumNumber = non-empty-array<StadiumNumber, ExpectedByRaceNumber>
 * @psalm-internal tests
 *
 * @author shimomo
 */
final class ScraperPsalmType
{
    //
}
