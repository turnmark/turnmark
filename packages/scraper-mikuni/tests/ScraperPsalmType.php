<?php

declare(strict_types=1);

namespace Turnmark\Scraper\Mikuni\Tests;

/**
 * @psalm-type Date = \Carbon\CarbonInterface
 * @psalm-type RaceNumber = int<1, 12>
 * @psalm-type RaceNumbers = list<int<1, 12>>
 * @psalm-type Arguments = array{Date, RaceNumber}
 * @psalm-type BatchArguments = array{Date, RaceNumbers}
 * @psalm-type Expected = non-empty-array<non-empty-string, mixed>
 * @psalm-type ExpectedByRaceNumber = non-empty-array<RaceNumber, Expected>
 * @psalm-internal tests
 *
 * @author shimomo
 */
final class ScraperPsalmType
{
    //
}
