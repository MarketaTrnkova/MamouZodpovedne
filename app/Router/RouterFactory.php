<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('diskuse/<title>[/<id>]', ['presenter' => 'Diskuse', 'action' => 'show']);
		$router->addRoute('diskuse', ['presenter' => 'Diskuse', 'action' => 'default']);
		$router->addRoute('<presenter>/<action>[/<id>]', ['presenter' => 'Domu', 'action' => 'default']);
		return $router;
	}
}
