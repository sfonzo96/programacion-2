<?php

namespace App\Services;

use App\Enums\CPUThresholds;
use App\Enums\Metrics;
use App\Enums\RAMThresholds;
use InvalidArgumentException;

class MetricsService
{
	public static function getMetricThresholdStatus(Metrics $metric, float $value): CPUThresholds|RAMThresholds
	{
		switch (strtolower($metric->name)) {
			case 'cpu':
				if ($value < CPUThresholds::WARNING->value) {
					return CPUThresholds::NORMAL;
				} elseif ($value < CPUThresholds::CRITICAL->value) {
					return CPUThresholds::WARNING;
				} else {
					return CPUThresholds::CRITICAL;
				}
			case 'ram':
				if ($value < RAMThresholds::WARNING->value) {
					return RAMThresholds::NORMAL;
				} elseif ($value < RAMThresholds::CRITICAL->value) {
					return RAMThresholds::WARNING;
				} else {
					return RAMThresholds::CRITICAL;
				}
			default:
				throw new InvalidArgumentException("Unknown metric name: $metric");
		}
	}
}
