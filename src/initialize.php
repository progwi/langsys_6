<?php
require_once "bootstrap.php";

use App\Services\RoleService;
use App\Services\UserService;

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

$roleService = new RoleService($entityManager);
$userService = new UserService($entityManager);

$initialRoles = json_decode(file_get_contents(__DIR__ . '/Models/roles.json'), true);

foreach ($initialRoles as $role) {
	$roleService->create($role);
}

$initialUsers = json_decode(file_get_contents(__DIR__ . '/Models/users.json'), true);

foreach ($initialUsers as $user) {
	$u = $userService->create($user);
}

$usersList = $userService->query(1);
$i = 0;
foreach ($usersList as $user) {
	echo ++$i . " " . $user->getName() . " - " . $user->getEmail() . "\n";
}