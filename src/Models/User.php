<?php
namespace App\Models;

use App\Models\Person;
use App\Models\Role;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements JsonSerializable
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $email;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $password;

	/**
	 * @ORM\OneToOne(targetEntity="Person", cascade={"remove"})
	 * @var Person
	 */
	private $person;

	/**
	 * @ORM\ManyToMany(targetEntity="Role")
	 * @var Role[] An ArrayCollection of Role objects.
	 */
	private $roles;

	public function __construct(
		$data = [
			"name"		=> "",
			"email"		=> "",
			"password"	=> ""
		]
	) {
		$this->name = $data["name"];
		$this->email = $data["email"];
		$this->password = $data["password"];
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function addRole(Role $role)
	{
		$this->roles[] = $role;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function setPerson(Person $person)
	{
		$this->person = $person;
		$person->setUser($this);
	}

	public function getPerson()
	{
		return $this->person;
	}

	public function __toString()
	{
		return $this->name . " (" . $this->email . ")";
	}

	public function jsonSerialize()
	{
		return [
			"id"		=> $this->id,
			"name"	=> $this->name,
			"email"	=> $this->email,
			"person"	=> $this->person,
			"maxRole"	=> $this->maxRole()->getName()
		];
	}

	public function maxRole()
	{
		$max = 0;
		$maxRole = null;
		foreach ($this->getRoles() as $role) {
			if ($role->getId() > $max) {
				$max = $role->getId();
				$maxRole = $role;
			}
		}
		return $maxRole;
	}
}
