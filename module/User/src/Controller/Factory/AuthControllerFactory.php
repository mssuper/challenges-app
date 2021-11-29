<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\AuthController;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Service\AuthManager;
use User\Service\UserManager;
/**
  * Esta é a fábrica do AuthController. Seu objetivo é instanciar o controlador
* e injetar dependências em seu construtor.
  */
class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authManager = $container->get(AuthManager::class);
        $userManager = $container->get(UserManager::class);

        return new AuthController($entityManager, $authManager, $userManager);
    }
}
