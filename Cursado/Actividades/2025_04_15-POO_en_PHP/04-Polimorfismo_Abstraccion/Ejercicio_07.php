<?php
	declare(strict_types=1);

	interface ICommunicable {
		public function sendMessage(string $content): void;
	}

	class Mail implements ICommunicable {
		public string $sender;

		public function __construct(string $sender) {
			$this->sender = $sender;
		}

		public function sendMessage(string $content): void {
			echo "Mail sent from:\t{$this->sender}.\nContent:\t{$content}.\nVia:\tGmail";	
		}
	}

	class Text implements ICommunicable {
		public string $sender;
		
		public function __construct(string $sender) {
			$this->sender = $sender;
		}

		public function sendMessage(string $content): void {
			echo "Text message sent from:\t{$this->sender}.\nContent:\t{$content}.";
		} 
	}

	$mail = new Mail("santiagofonzo@live.com");
	$text = new Text("3417123123");
	$communicables = [$mail, $text];

	foreach ($communicables as $communicable) {
		$communicable->sendMessage("Test message");
		echo "\n";
	}
?>
