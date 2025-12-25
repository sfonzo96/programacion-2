<?php

namespace App\Enums;

enum RAMThresholds: int
{
	case NORMAL = 60;
	case WARNING = 70;
	case CRITICAL = 85;
}
