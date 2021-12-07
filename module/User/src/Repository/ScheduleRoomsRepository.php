<?php


namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\ScheduleRooms;


/**
 * Esta é a classe de repositório customizada para entidade de Salas.
 */
class ScheduleRoomsRepository extends EntityRepository
{
    /**
     * Recupera todas as agendas em ordem decrescente de dateCreated.
     * @return Query
     */
    public function findAllscheduledRooms()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(ScheduleRooms::class, 'u')
            ->orderBy('u.dateCreated', 'DESC');

        return $queryBuilder->getQuery();
    }
}