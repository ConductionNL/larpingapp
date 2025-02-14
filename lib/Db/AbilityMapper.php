<?php

namespace OCA\LarpingApp\Db;

use OCA\LarpingApp\Db\Ability;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class AbilityMapper extends QBMapper
{
	/**
	 * The name of the database table for abilities
	 * 
	 * @var string
	 * @psalm-var string
	 * @phpstan-var string
	 */
	private const TABLE_NAME = 'larpingapp_abilities';
	
	public function __construct(IDBConnection $db)
	{
		parent::__construct($db, 'larpingapp_abilities');
	}

	public function find(int $id): Ability
	{
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from('larpingapp_abilities')
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);

		return $this->findEntity(query: $qb);
	}

	public function findAll(?int $limit = null, ?int $offset = null, ?array $filters = [], ?array $searchConditions = [], ?array $searchParams = []): array
	{
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from('larpingapp_abilities')
			->setMaxResults($limit)
			->setFirstResult($offset);

        foreach($filters as $filter => $value) {
			if ($value === 'IS NOT NULL') {
				$qb->andWhere($qb->expr()->isNotNull($filter));
			} elseif ($value === 'IS NULL') {
				$qb->andWhere($qb->expr()->isNull($filter));
			} else {
				$qb->andWhere($qb->expr()->eq($filter, $qb->createNamedParameter($value)));
			}
        }

        if (!empty($searchConditions)) {
            $qb->andWhere('(' . implode(' OR ', $searchConditions) . ')');
            foreach ($searchParams as $param => $value) {
                $qb->setParameter($param, $value);
            }
        }

		return $this->findEntities(query: $qb);
	}

	public function createFromArray(array $object): Ability
	{
		$ability = new Ability();
		$ability->hydrate(object: $object);
		return $this->insert(entity: $ability);
	}

	public function updateFromArray(int $id, array $object): Ability
	{
		$ability = $this->find($id);
		$ability->hydrate($object);

		return $this->update($ability);
	}
}
