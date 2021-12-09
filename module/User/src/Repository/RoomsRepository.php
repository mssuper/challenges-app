<?php

namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\Rooms;


/**
 * Esta é a classe de repositório customizada para entidade de Salas.
 */
class RoomsRepository extends EntityRepository
{
    /**
     * Recupera todas as salass em ordem decrescente de dateCreated.
     * @return Query
     */
    public function findAllRooms()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Rooms::class, 'u')
            ->orderBy('u.dateCreated', 'DESC');

        return $queryBuilder->getQuery();
    }

    /**
     * Retorna todos os registros
     * @return array
     */
    public function returnAllRooms()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Rooms::class, 'u')
            ->orderBy('u.dateCreated', 'ASC');
        return $queryBuilder->getQuery()->getArrayResult();
    }
}