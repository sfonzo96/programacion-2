<?php
	class BankAccount {
		private $balance = 0;
		
		public function __construct($balance) {
			$this->balance = $balance;
		}

		public function getBalance() {
			return $this->balance;
		}

		public function deposit($amount) {
			if (!($amount > 0)) {
				echo "Amount should be positive!";
				exit(1);
			}

			$this->balance += $amount;
		}
	}

	$bankAccount = new BankAccount(200);
	$bankAccount->deposit(100);
	echo "Balance:\t{$bankAccount->getBalance()}\n";
?>
