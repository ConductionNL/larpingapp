<?php

namespace OCA\OpenCatalogi\Db;

use DateTime;
use JsonSerializable;
use OCP\AppFramework\Db\Entity;

class Skill extends Entity implements JsonSerializable
{

	protected ?string $id = null;
	protected ?string $name = null;
	protected ?string $description = null;
	protected ?string $effect = null;
	protected ?array $effects = null;
	protected ?array $requiredSkills = [];
	protected ?array $requiredStats = [];
	protected ?array $requiredConditions = [];
	protected ?array $requiredEffects = [];
	protected ?int $requiredScore = null;

	public function __construct() {
		$this->addType('id', 'string');
		$this->addType('name', 'string');
		$this->addType('description', 'string');
		$this->addType('effect', 'string');
		$this->addType('effects', 'json');
		$this->addType('requiredSkills', 'array');
		$this->addType('requiredStats', 'array');
		$this->addType('requiredConditions', 'array');
		$this->addType('requiredEffects', 'array');
		$this->addType('requiredScore', 'integer');
	}

	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'effect' => $this->effect,
			'effects' => $this->effects,
			'requiredSkills' => $this->requiredSkills,
			'requiredStats' => $this->requiredStats,
			'requiredConditions' => $this->requiredConditions,
			'requiredEffects' => $this->requiredEffects,
			'requiredScore' => $this->requiredScore,
		];
	}
}