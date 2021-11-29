<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\AuthManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Esta é a classe de fábrica para o serviço AuthManager. O propósito da fábrica
 * é instanciar o serviço e passar dependências (injetar dependências).
 */
class AuthManagerFactory implements FactoryInterface
{
    /**
     * Este método cria o serviço AuthManager e retorna sua instância.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instancie dependências.
        $authenticationService = $container->get(\Zend\Authentication\AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);

        // Obter o conteúdo da chave de configuração 'access_filter' (o serviço AuthManager
        // usará esses dados para determinar se permitirá o usuário atualmente conectado
        // para executar a ação do controlador ou não.
        $config = $container->get('Config');
        if (isset($config['access_filter']))
            $config = $config['access_filter'];
        else
            $config = [];

        // Instancie o serviço AuthManager e injete dependências em seu construtor.
        return new AuthManager($authenticationService, $sessionManager, $config);
    }
}
