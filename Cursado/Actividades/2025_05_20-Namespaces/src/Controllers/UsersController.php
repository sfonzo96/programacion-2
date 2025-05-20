<?php

declare(strict_types=1);

namespace Controllers;

use Models\User;

class UsersController
{
	public function init(): void
	{
		echo "Página de usuarios.\n";
	}

	public function showUserName(User $user)
	{
		echo "The user's name is {$user->getName()}.\n";
	}
}
