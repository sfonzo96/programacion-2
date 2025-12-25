<?php

namespace App\Enums;

enum UsersRoles: int
{
	case ADMIN = 1;
	case MANAGER = 2;
	case WATCHER = 3;
	case DAEMON = 4;
	case ALL = 5;
}
