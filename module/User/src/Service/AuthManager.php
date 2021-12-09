<?php

namespace User\Service;

use Zend\Authentication\Result;

/**
 * O serviço AuthManager é responsável pelo login / logout do usuário e acesso simples
 * filtragem. O recurso de filtragem de acesso verifica se o visitante atual
 * tem permissão para ver a página fornecida ou não.
 */
class AuthManager
{
    /**
     * Serviço de autenticação.
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authService;

    /**
     * Gerenciador de sessão.
     * @var Zend\Session\SessionManager
     */
    private $sessionManager;

    /**
     * Conteúdo da chave de configuração 'access_filter'.
     * @var array
     */
    private $config;

    /**
     * Constrói o serviço.
     */
    public function __construct($authService, $sessionManager, $config)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
    }

    /**
     * Executa uma tentativa de login. Se o argumento $ rememberMe for verdadeiro, ele força a sessão
     * para durar um mês (caso contrário, a sessão expira em uma hora).
     */
    public function login($email, $password, $rememberMe)
    {
        // Verifique se o usuário já está logado. Em caso afirmativo, não permita o login
        // duas vezes.
        if ($this->authService->getIdentity() != null) {
            throw new \Exception('já logado');
        }

        // Autentica com login / senha.
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);
        $result = $this->authService->authenticate();

        // Se o usuário quiser "lembrar-se dele", faremos com que a sessão expire em
        // um mês. Por padrão, a sessão expira em 1 hora (conforme especificado em nosso
        // arquivo config / global.php).
        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            // O cookie da sessão expira em 1 mês (30 dias).
            $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
        }

        return $result;
    }

    /**
     * Executa o logout do usuário.
     */
    public function logout()
    {
        // Permitir o logout apenas quando o usuário estiver logado.
        if ($this->authService->getIdentity() == null) {
            throw new \Exception('O usuário não está logado');
        }

        // Remova a identidade da sessão.
        $this->authService->clearIdentity();
    }

    /**
     * Este é um filtro de controle de acesso simples. É capaz de restringir os não autorizados
     * usuários para visitar certas páginas.
     *
     * Este método usa a chave 'access_filter' no arquivo de configuração e determina
     * onde o visitante atual tem permissão para acessar a determinada ação do controlador
     * ou não. Retorna verdadeiro se permitido; caso contrário, false.
     */
    public function filterAccess($controllerName, $actionName)
    {
        // Modo de determinação - 'restritivo' (padrão) ou 'permissivo'. Em restritivo
        // modo todas as ações do controlador devem ser explicitamente listadas no 'access_filter'
        // chave de configuração, e o acesso é negado a qualquer ação não listada para usuários não autorizados.
        // No modo permissivo, se uma ação não estiver listada na chave 'access_filter',
        // o acesso a ele é permitido a qualquer pessoa (mesmo para usuários não logados.
        // O modo restritivo é mais seguro e recomendado para uso.
        $mode = isset($this->config['options']['mode']) ? $this->config['options']['mode'] : 'restrictive';
        if ($mode != 'restrictive' && $mode != 'permissive')
            throw new \Exception('Modo de filtro de acesso inválido (esperado modo restritivo ou permissivo');

        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList == '*') {
                    if ($allow == '*')
                        return true; // Anyone is allowed to see the page.
                    else if ($allow == '@' && $this->authService->hasIdentity()) {
                        return true; // Only authenticated user is allowed to see the page.
                    } else {
                        return false; // Access denied.
                    }
                }
            }
        }

        // No modo restritivo, proibimos o acesso de usuários não autorizados a qualquer
        // ação não listada na chave 'access_filter' (por razões de segurança).
        if ($mode == 'restrictive' && !$this->authService->hasIdentity())
            return false;

        // Permitir acesso a esta página.
        return true;
    }
}