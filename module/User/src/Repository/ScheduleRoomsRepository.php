<?php

namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\Rooms;
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
        $sql='
        SELECT
            user.email,
            rooms.room_name,
            schedulerooms.datetime_in,
            schedulerooms.datetime_out,
            schedulerooms.idscheduleroom
        FROM rooms
        INNER JOIN schedulerooms
            ON rooms.idroom = schedulerooms.idroom
        INNER JOIN user
            ON user.id = schedulerooms.iduser
        ORDER BY schedulerooms.datetime_in DESC';
        $conn = $this->getEntityManager()
            ->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function findinvaliduser($id)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(ScheduleRooms::class, 'u')
            ->orderBy('u.dateCreated', 'DESC')
            ->Where('u.id = '.$id);
        return $queryBuilder->getQuery();
    }
}