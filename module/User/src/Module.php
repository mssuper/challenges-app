<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use User\Controller\AuthController;
use User\Service\AuthManager;

class Module
{
    /**
     * Este método retorna o caminho para o arquivo module.config.php.
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /**
     * Este método é chamado quando o bootstrap do MVC é concluído e permite
     * para registrar ouvintes de eventos.
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Obtenha o gerenciador de eventos.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Registre o método de ouvinte de evento.
        $sharedEventManager->attach(AbstractActionController::class, 
                MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
        
        $sessionManager = $event->getApplication()->getServiceManager()->get('Zend\Session\SessionManager');
        
        $this->forgetInvalidSession($sessionManager);
    }
    
    protected function forgetInvalidSession($sessionManager) 
    {
    	try {
    		$sessionManager->start();
    		return;
    	} catch (\Exception $e) {
    	}
    	/**
         * A validação da sessão falhou: brinde e continue.
    	 */
    	// @codeCoverageIgnoreStart
    	session_unset();
    	// @codeCoverageIgnoreEnd
    }
    
    /**
     * Método de listener de eventos para o evento 'Dispatch'. Nós ouvimos o despacho
     * evento para chamar o filtro de acesso. O filtro de acesso permite determinar se
     * o visitante atual tem permissão para ver a página ou não. Se ele / ela
     * não está autorizado e não tem permissão para ver a página, nós redirecionamos o usuário
     * para a página de login.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Obtenha o controlador e a ação para a qual a solicitação HTTP foi despachada.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Converte o nome da ação estilo traço em caixa de camelo.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Obtenha a instância do serviço AuthManager.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Execute o filtro de acesso em cada controlador, exceto AuthController
        // (para evitar redirecionamento infinito).
        if ($controllerName!=AuthController::class && 
            !$authManager->filterAccess($controllerName, $actionName)) {

            // Lembre-se do URL da página que o usuário tentou acessar. Nós vamos
            // redireciona o usuário para aquele URL após o login bem-sucedido.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Tornar o URL relativo (remover esquema, informações do usuário, nome do host e porta)
            // para evitar o redirecionamento para outro domínio por um usuário malicioso.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // Redirecione o usuário para a página "Login".
            return $controller->redirect()->toRoute('login', [], 
                    ['query'=>['redirectUrl'=>$redirectUrl]]);
        }
    }
}
