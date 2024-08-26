<?php

namespace OCA\OpenCatalogi\Db;

use DateTime;
use JsonSerializable;
use OCP\AppFramework\Db\Entity;

class Template extends Entity implements JsonSerializable
{
	protected ?string $id = null;
	protected ?string $name = null;
	protected ?string $description = null;
	protected ?string $template = null;

	public function __construct() {
		$this->addType('id', 'string');
		$this->addType('name', 'string');
		$this->addType('description', 'string');
		$this->addType('template', 'string');
	}

	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'template' => $this->template,
		];
	}
}