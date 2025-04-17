<?php 
	class CurrentAccount {
		private $balance;
		private $limit; // Allowed negative balance?

		public function __construct($balance = 0, $limit = 0) {
			$this->balance = $balance;
			$this->limit = $limit;
		}

		public function getBalance() {
			return $this->balance;
		}

		public function withdrawl($amount) {
			if ($amount > $this->balance + $this->limit) {
				$max = $this->balance + $this->limit;
				echo "Your current max available withdrawl amount is {$max}. You've tried with {$amount}\n";
				return;
			}

			$this->balance -= $amount;
		}
	}

	$account = new CurrentAccount(15000, 2000);
	echo "Current balance:\t\t$ {$account->getBalance()}.\n"; // balance: 15000
	$account->withdrawl(18000); // Should not work
	echo "Current balance:\t\t$ {$account->getBalance()}.\n"; // balance: 15000
	$account->withdrawl(5000); // balance: 15000 - 5000 = 10000
	echo "Current balance:\t\t$ {$account->getBalance()}.\n"; // balance: 10000
?>
