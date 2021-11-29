<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

class Module
{
    const VERSION = '3.0.0dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Este método é chamado assim que o bootstrap do MVC for concluído.
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // A seguinte linha instancia o SessionManager e automaticamente
        // torna o SessionManager o 'padrão' para evitar passar o
        // gerenciador de sessão como uma dependência de outros modelos.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }
}

