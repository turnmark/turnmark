<?php

declare(strict_types=1);

use Turnmark\Scraper\Scraper;

require __DIR__ . '/../vendor/autoload.php';

/**
 * The suite answers every request from a fixture, so the interval between calls is only paid in
 * waiting. It is taken down to the shortest the package allows rather than left at the default.
 */
Scraper::setMinCallIntervalSeconds(1.0);
