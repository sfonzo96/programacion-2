<?php
	class Transaction {
		public $type; // Inflow/outflow
		public $amount;
		
		public function __construct($type, $amount) {
			$this->type = $type;
			$this->amount = $amount;
		}
	}

	class Account {
		public $balance = 0;
		public $transactions = [];

		public function showTransactions() {
			foreach ($this->transactions as $transaction) {
				echo "Transaction:\t{$transaction->type}\t($transaction->amount)\n";
			};
		}

		public function showBalance() {
			$this->showTransactions();
			echo "Account balance:\t{$this->balance}\n";
		}

		public function deposit($amount) {
			array_push($this->transactions, new Transaction('Inflow', $amount));
			$this->balance += $amount;
		}
		
		public function withdraw($amount) {
			if ($this->balance - $amount < 0) {
				echo "Balance for withdrawl is not enough.\n";
				return;
			}
			
			array_push($this->transactions, new Transaction('Outflow', $amount));
			$this->balance -= $amount;
		}	
	}
	
	$account = new Account();
	$account->deposit(50000);
	$account->withdraw(27000); // Balance 23000
	$account->withdraw(100000); // Deberia dar error
	$account->deposit(7000); // Balance 30000
	$account->showBalance();
?>
