<?php
require_once "db.php";
require_once "DAO/UserDAO.php";
require_once "Models/User.php";

use Database;
use DAO\UserDAO;
use Models\User;

$pdo = Database::getPDOInstance();
$userDAO = new UserDAO($pdo);

$newStatus = "active";
$userId = "1";

$mail = "agomez@institutozonaoeste.edu.ar";

$success = $userDAO->createUser(new User($newStatus, $mail));
if (!$success) {
	echo "User with mail {$mail} couldn't be created";
} else {
	echo "User with mail {$mail} created successfully";
}

$user = $userDAO->getUserById($userId);
echo "Before update:\n\tEmail:\t{$user->email}\n\tStatus:\t{$user->status}\n";
$success = $userDAO->updateUserStatus($newStatus, $userId);
$user = $userDAO->getUserById($userId);
echo "After update:\n\tEmail:\t{$user->email}\n\tStatus:\t{$user->status}\n";
