<?php

namespace App\Middlewares;

use Tuupola\Middleware\HttpBasicAuthentication\AuthenticatorInterface as IAuthenticator;
use App\Repositories\UsersRepository;

class BasicAuthenticator implements IAuthenticator
{
	private UsersRepository $usersRepository;

	public function __construct(UsersRepository $usersRepository)
	{
		$this->usersRepository = $usersRepository;
	}

	public function __invoke(array $arguments): bool
	{
		$user = $this->usersRepository->getByUsername($arguments["user"]);
		if (!$user) {
			return false;
		}

		return password_verify($arguments["password"], $user->password);
	}
}
