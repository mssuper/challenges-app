<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\AuthAdapter;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Esta é a classe de fábrica para o serviço AuthAdapter. O propósito da fábrica
 * é instanciar o serviço e passar dependências (injetar dependências).
 */
class AuthAdapterFactory implements FactoryInterface
{
    /**
     * Este método cria o serviço AuthAdapter e retorna sua instância.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Obter o gerenciador de entidades Doctrine do Service Manager.
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Crie o AuthAdapter e injete dependência em seu construtor.
        return new AuthAdapter($entityManager);
    }
}
