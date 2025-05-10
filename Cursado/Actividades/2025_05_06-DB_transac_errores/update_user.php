<?php
require_once "db.php";
require_once "DAO/UserDAO.php";
require_once "Models/User.php";

$pdo = Database::getPDOInstance();
$userDAO = new UserDAO($pdo);

$newStatus = "active";
$userId = "1";

$user = $userDAO->getUserById($userId);
echo "Before update:\n\tEmail:\t{$user->email}\n\tStatus:\t{$user->status}\n";
$success = $userDAO->updateUserStatus($newStatus, $userId);
$user = $userDAO->getUserById($userId);
echo "After update:\n\tEmail:\t{$user->email}\n\tStatus:\t{$user->status}\n";
