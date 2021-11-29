<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
use User\Entity\User;

/**
 * Este assistente de visualização é usado para recuperar a entidade do usuário do usuário conectado no momento.
 */
class CurrentUser extends AbstractHelper 
{
    /**
     * Gerenciador de entidade.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Serviço de autenticação.
     * @var Zend\Authentication\AuthenticationService
     */
    private $authService;
    
    /**
     * Entidade de usuário obtida anteriormente.
     * @var User\Entity\User
     */
    private $user = null;
    
    /**
     * Construtor.
     */
    public function __construct($entityManager, $authService) 
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }
    
    /**
     * Retorna o usuário atual ou nulo se não estiver logado.
     * @param bool $ useCachedUser Se verdadeiro, a entidade User é buscada apenas na primeira chamada (e armazenada em cache nas chamadas subsequentes).
     * @return User | null
     */
    public function __invoke($useCachedUser = true)
    {
        // Verifique se o usuário já foi buscado anteriormente.
        if ($useCachedUser && $this->user!==null)
            return $this->user;

        // Verifique se o usuário está logado.
        if ($this->authService->hasIdentity()) {

            // Buscar entidade de usuário do banco de dados.
            $this->user = $this->entityManager->getRepository(User::class)->findOneBy(array(
                'email' => $this->authService->getIdentity()
            ));
            if ($this->user==null) {
                // Opa.. a identidade é apresentada na sessão, mas não existe tal usuário no banco de dados.
                // Lançamos uma exceção, porque este é um possível problema de segurança.
                throw new \Exception('Not found user with such ID');
            }

            // Retorna a entidade User que encontramos.
            return $this->user;
        }
        
        return null;
    }
}
