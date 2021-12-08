<?php

namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\UserController;
use User\Service\UserManager;
use User\Service\RoomsManager;
use User\Service\ScheduleRoomsManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Esta é a fábrica do UserController. Seu objetivo é instanciar o
 * controlador e injetar dependências nele.
 */
class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        $RoomsManager = $container->get(RoomsManager::class);
        $ScheduleRoomsManager =  $container->get(ScheduleRoomsManager::class);

        // Instancie o controlador e injete dependências
        return new UserController($entityManager, $userManager, $RoomsManager, $ScheduleRoomsManager);
    }
}