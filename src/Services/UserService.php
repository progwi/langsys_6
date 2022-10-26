<?php
namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Person;

class UserService
{
	private $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function create($userData = [
		"name" => "",
		"email" => "",
		"password" => "",
		"roles" => [],
		"person" => [
			"firstName" => "",
			"lastName" => "",
			"height" => 0,
			"birthDate" => ""
		]
	])
	{
		$user = $this->alreadyExists($userData);
		if ($user) {
			return $user;
		}
		$userInfo = [
			'name' => $userData['name'],
			'email' => $userData['email'],
			'password' => $userData['password'] ?? '123456',
		];
		$newUser = new User($userInfo);
		$newPerson = new Person($userData['person']);
		$newUser->setPerson($newPerson);
		$this->entityManager->persist($newUser);
		$this->entityManager->persist($newPerson);
		$this->setRoles($newUser, $userData['roles']);
		$this->entityManager->flush();
		return $newUser;
	}

	public function alreadyExists($data = [
		"email" => ""
	])
	{
		$existingUser = $this->entityManager->getRepository(User::class)->findOneBy([
			"email" => $data["email"]
		]);
		return $existingUser;
	}

	public function setRoles($user, $roles = [])
	{
		// $user->clearRoles();
		foreach ($roles as $roleId) {
			$roleEntity = $this->entityManager->getRepository(Role::class)->findOneBy(
				['id' => $roleId]
			);
			if ($roleEntity) {
				$user->addRole($roleEntity);
			}
		}
		$this->entityManager->flush();
	}

	public function getUsers()
	{
		$userRepository = $this->entityManager->getRepository(User::class);
		$users = $userRepository->findAll();
		return $users;
	}

	public function getUser($id)
	{
		$userRepository = $this->entityManager->getRepository(User::class);
		$user = $userRepository->find($id);
		return $user;
	}

	public function updateUser($user)
	{
		$this->entityManager->merge($user);
		$this->entityManager->flush();
	}

	public function deleteUser($user)
	{
		$this->entityManager->remove($user);
		$this->entityManager->flush();
	}

	public function jsonQuery()
	{
		$list = $this->query();
		$users = json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		return $users;
	}

	public function query($pageNumber = 1, $pageSize = 10)
	{
		$offset = ($pageNumber - 1) * $pageSize;
		$dql = "SELECT u FROM App\Models\User u ORDER BY u.name";
		$query = $this->entityManager->createQuery($dql)
			->setFirstResult($offset)
			->setMaxResults($pageSize);
		$users = $query->getResult();
		return $users;
	}
}