<?php

namespace OCA\OpenCatalogi\Db;

use DateTime;
use JsonSerializable;
use OCP\AppFramework\Db\Entity;

class Effect extends Entity implements JsonSerializable
{

	protected ?string $id = null;
	protected ?string $name = null;
	protected ?string $description = null;
	protected ?string $statId = null;
	protected ?int $modifier = null;
	protected ?string $modification = 'positive';
	protected ?string $cumulative = 'non-cumulative';

	public function __construct() {
		$this->addType('id', 'string');
		$this->addType('name', 'string');
		$this->addType('description', 'string');
		$this->addType('statId', 'string');
		$this->addType('modifier', 'integer');
		$this->addType('modification', 'string');
		$this->addType('cumulative', 'string');
	}

	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'statId' => $this->statId,
			'modifier' => $this->modifier,
			'modification' => $this->modification,
			'cumulative' => $this->cumulative,
		];
	}
}