<?php
	declare(strict_types=1);

	interface Playable {
		public function play(): void;
	}

	class Movie implements Playable {
		public string $name;
		public int $duration; // minutes;

		public function __construct(string $name, int $duration) {
			$this->name = $name;
			$this->duration = $duration;
		}

		public function play(): void {
			echo "{$this->name} is playing for {$this->duration} minutes.";
		}
	}

	class Podcast implements Playable {
		public string $name;
		public string $host;
		public string $guest;
		
		public function __construct(string $name, string $host, string $guest) {
			$this->name = $name;
			$this->host = $host;
			$this->guest = $guest;
		}

		public function play(): void {
			echo "{$this->name} is hosted by {$this->host} and today's guest is {$this->guest}.";
		}
	}

	$movie = new Movie("Idiocracy", 84);
	$podcast = new Podcast("Modern Wisdom", "Chris Williamson", "Dr. K HealthyGamer");
	$playables = [$movie, $podcast];

	foreach ($playables as $playable) {
		$playable->play();
		echo "\n";
	}
?>
