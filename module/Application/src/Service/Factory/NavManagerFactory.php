<?php

namespace Application\Service\Factory;

use Application\Service\NavManager;
use Interop\Container\ContainerInterface;

/**
 * Esta é a classe de fábrica para o serviço NavManager. O propósito da fábrica
 * é instanciar o serviço e passar dependências (injetar dependências).
 */
class NavManagerFactory
{
    /**
     * Este método cria o serviço NavManager e retorna sua instância.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);

        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');

        return new NavManager($authService, $urlHelper);
    }
}
