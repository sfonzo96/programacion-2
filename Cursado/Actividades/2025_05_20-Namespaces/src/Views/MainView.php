<?php

declare(strict_types=1);

namespace Views;

use Interfaces\Renderable;

class MainView implements Renderable
{

	public function render(): void
	{
		echo "Main view is rendering.\n";
	}
}
