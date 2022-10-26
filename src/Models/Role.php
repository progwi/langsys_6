<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role implements JsonSerializable
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	public function __construct(
		$data = [
			"id"		=> 0,
			"name"	=> ""
		]
	) {
		$this->id = $data["id"];
		$this->name = $data["name"];
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function __toString()
	{
		return $this->name;
	}

	public function jsonSerialize()
	{
		return [
			"id"	=> $this->id,
			"name"	=> $this->name
		];
	}
}
