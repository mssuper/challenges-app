<?php

namespace User\Controller\Plugin;

use User\Entity\User;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Este plugin de controle é projetado para permitir que você obtenha a entidade de usuário atualmente conectada
 * dentro do seu controlador.
 */
class CurrentUserPlugin extends AbstractPlugin
{
    /**
     * Gerente de entidade.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Serviço de autenticação.
     * @var Zend\Authentication\AuthenticationService
     */
    private $authService;

    /**
     * Usuário conectado.
     * @var User\Entity\User
     */
    private $user = null;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    /**
     * Este método é chamado quando você invoca este plugin em seu controlador: $ user = $ this-> currentUser ();
     * @param bool $useCachedUser Se verdadeiro, a entidade Usuário é buscada apenas na primeira chamada (e armazenada em cache nas chamadas subsequentes).
     * @return User|null
     */
    public function __invoke($useCachedUser = true)
    {
        // Se o usuário atual já foi buscado, retorne-o.
        if ($useCachedUser && $this->user !== null)
            return $this->user;

        // Verifique se o usuário está logado.
        if ($this->authService->hasIdentity()) {

            // Buscar entidade de usuário do banco de dados.
            $this->user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($this->authService->getIdentity());
            if ($this->user == null) {
                // Opa .. a identidade é apresentada na sessão, mas não existe tal usuário no banco de dados.
                // Lançamos uma exceção, porque esse é um possível problema de segurança.
                throw new \Exception('Usuário não encontrado com esse e-mail');
            }

            // Retornar usuário encontrado.
            return $this->user;
        }

        return null;
    }
}



