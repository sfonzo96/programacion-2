<?php

namespace App\Utils;


class NetworksUtils
{
	public static function isNetworkOnline(string $ip_address, string $cidrMask): bool
	{
		$fullCidr = "{$ip_address}/{$cidrMask}";
		$resultCode = null;
		$command = __DIR__ . "/../Utils/Scripts/networkAvailable.sh " . "{$fullCidr}";
		exec($command, $output, $resultCode);

		return $resultCode == 0;
	}
}
