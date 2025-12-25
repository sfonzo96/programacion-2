<?php

namespace App\Enums;

enum CPUThresholds: int
{
	case NORMAL = 20;
	case WARNING = 50;
	case CRITICAL = 80;
}
