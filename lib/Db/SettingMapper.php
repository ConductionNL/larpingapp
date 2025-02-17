<?php

namespace OCA\LarpingApp\Db;

use OCA\LarpingApp\Db\Setting;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class SettingMapper extends QBMapper
{
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'larpingapp_settings');
    }

    public function find(int $id): Setting
    {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from('larpingapp_settings')
            ->where(
                $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
            );

        return $this->findEntity(query: $qb);
    }

    public function findAll(?int $limit = null, ?int $offset = null, ?array $filters = [], ?array $searchConditions = [], ?array $searchParams = []): array
    {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from('larpingapp_settings')
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

    public function createFromArray(array $object): Setting
    {
        $setting = new Setting();
        $setting->hydrate(object: $object);
        return $this->insert(entity: $setting);
    }

    public function updateFromArray(int $id, array $object): Setting
    {
        $setting = $this->find($id);
        $setting->hydrate($object);

        return $this->update($setting);
    }
}
