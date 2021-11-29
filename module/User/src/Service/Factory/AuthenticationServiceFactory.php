<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Fábrica responsável pela criação do serviço de autenticação.
 */
class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * Este método cria o serviço Zend \ Authentication \ AuthenticationService
     * e retorna sua instância.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new SessionStorage('Zend_Auth', 'session', $sessionManager);
        $authAdapter = $container->get(AuthAdapter::class);

        // Crie o serviço e injete dependências em seu construtor.
        return new AuthenticationService($authStorage, $authAdapter);
    }
}

