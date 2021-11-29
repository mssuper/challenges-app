<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\UserManager;

/**
 * Esta é a classe de fábrica para o serviço UserManager. O propósito da fábrica
 * é instanciar o serviço e passar dependências (injetar dependências).
 */
class UserManagerFactory
{
    /**
     * Este método cria o serviço UserManager e retorna sua instância.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('Config');

        return new UserManager($entityManager, $viewRenderer, $config);
    }
}
