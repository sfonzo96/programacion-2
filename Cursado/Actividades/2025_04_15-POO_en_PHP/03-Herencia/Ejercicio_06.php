<?php

	declare(strict_types=1);	

	class Account {
		public float $balance;
		
		public function __construct(float $balance) {
			$this->balance = $balance;
		}

		public function deposit(float $amount) {
			if ($amount <= 0) {
				echo "Amount to be deposited should be a positive value. You've tried with {$amount}.\n";
				return;
			}

			$this->balance += $amount;
		}

		public function withdraw(float $amount) {
			if ($amount > $this->balance) {
				echo "Amount to be withdrawl should be less than or equal to your balance, which is {$this->balance}.\n";
				return;
			}

			$this->balance -= $amount;
		}
	}

	class PremiumAccount extends Account {
		public float $bonification;

		public function __construct(float $amount, float $bonification) {
			parent::__construct($amount);
			$this->bonification = $bonification;
		}

		public function applyBonification() {
			$this->balance += $this->bonification;
			return $this->balance;
		}
	}
	
	$premiumAccount = new PremiumAccount(5000, 500);
	echo "Account balance:\t$ {$premiumAccount->balance}\n";
	$premiumAccount->applyBonification();
	echo "Account balance:\t$ {$premiumAccount->balance}\n";
?>
