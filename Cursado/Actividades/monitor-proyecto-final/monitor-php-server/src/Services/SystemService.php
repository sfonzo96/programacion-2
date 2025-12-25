<?php

namespace App\Services;

use App\Models\ProcessesSnapshot;
use Exception;
use App\Models\SystemInfo;
use DateTime;

class SystemService
{
	private static string $cacheDir = __DIR__ . "/../Cache/";

	public static function getSystemInfo(): SystemInfo
	{
		try {
			$infoFileName = self::$cacheDir . "systeminfo.json";
			if (file_exists($infoFileName) && filesize($infoFileName) != 0 && filemtime($infoFileName) + (3600 * 24)) {
				$sysinfo = json_decode(file_get_contents($infoFileName));
				return new SystemInfo(
					$sysinfo->hostname,
					$sysinfo->os,
					$sysinfo->uptime,
					$sysinfo->cpuModel,
					$sysinfo->cpuCores,
					new DateTime($sysinfo->timestamp)
				);
			} else {
				error_log(self::$cacheDir);
				$sysinfo = self::collectSystemInfo();
				file_put_contents($infoFileName, json_encode($sysinfo));
				return new SystemInfo(
					$sysinfo['hostname'],
					$sysinfo['os'],
					$sysinfo['uptime'],
					$sysinfo['cpuModel'],
					$sysinfo['cpuCores'],
					new DateTime($sysinfo['timestamp'])
				);
			}
		} catch (Exception $e) {
			error_log("Error fetching system info: " . $e->getMessage());
			return new SystemInfo(
				'unknown',
				'unknown',
				'unknown',
				'unknown',
				0,
				new DateTime()
			);
		}
	}

	public static function snapshotProcesses(): ProcessesSnapshot
	{
		$command = "ps auxf";
		exec($command, $output, $exit_code);
		if ($exit_code !== 0) {
			throw new Exception("Failed to execute command: $command");
		}

		$fullOutput = implode("\n", $output);

		return new ProcessesSnapshot($fullOutput);
	}

	private static function collectSystemInfo()
	{
		return [
			'hostname'   => php_uname('n'),
			'os'         => php_uname('s') . " " . php_uname('r'),
			'uptime'     => trim(shell_exec("uptime -p") ?? ""),
			'cpuModel'  => trim(shell_exec("lscpu | grep 'Model name' | awk -F: '{print $2}'") ?? ""),
			'cpuCores'  => (int) trim(shell_exec("nproc") ?? ""),
			'timestamp' => (new DateTime())->format('Y-m-d H:i:s.u')
		];
	}
}
