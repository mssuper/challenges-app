<?php
namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\User;

/**
 * Esta é a classe de repositório customizada para entidade de usuário.
 */
class UserRepository extends EntityRepository
{
    /**
     * Recupera todos os usuários em ordem decrescente de dateCreated.
     * @return Query
     */
    public function findAllUsers()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('u')
            ->from(User::class, 'u')
            ->orderBy('u.dateCreated', 'DESC');
        
        return $queryBuilder->getQuery();
    }
}