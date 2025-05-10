<?php
require_once "db.php";
require_once "DAO/AccountDao.php";
require_once "Models/Account.php";

$pdo = Database::getPDOInstance();
$accountDAO = new AccountDAO($pdo);
$originId = 1;
$destinationId = 2;
$transferAmount = 100;

function printAccountInfo(Account $account): void
{
	echo "Account {$account->id}'s balance: {$account->balance}\n";
}

// Before transfer
$originAccount = $accountDAO->getAccountById($originId);
$destinationAccount = $accountDAO->getAccountById($destinationId);

printAccountInfo($originAccount);
printAccountInfo($destinationAccount);
echo "\n";

$success = $accountDAO->transferBalance($originId, $destinationId, $transferAmount);
if (!$success) {
	echo "Transfer went wrong. Do both accounts exist? Or maybe balance wasn't enough.\n"; // Podría retornar un objeto con el estado (bool) y la causa
} else {
	// After transfer
	$originAccount = $accountDAO->getAccountById($originId);
	$destinationAccount = $accountDAO->getAccountById($destinationId);

	printAccountInfo($originAccount);
	printAccountInfo($destinationAccount);
}
