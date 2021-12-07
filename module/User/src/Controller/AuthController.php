<?php

namespace User\Controller;

use User\Form\LoginForm;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;

/**
 * Este controlador é responsável por permitir que o usuário efetue login e logout.
 */
class AuthController extends AbstractActionController
{
    /**
     * Gerente de entidade.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Gerenciador de autenticação.
     * @var User\Service\AuthManager
     */
    private $authManager;

    /**
     * Gerenciador de usuários.
     * @var User\Service\UserManager
     */
    private $userManager;

    /**
     * Construtor.
     */
    public function __construct($entityManager, $authManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
    }

    /**
     * Autentica o endereço de e-mail e as credenciais de senha fornecidos pelo usuário.
     */
    public function loginAction()
    {
        // Recupere o URL de redirecionamento (se aprovado). Vamos redirecionar o usuário para este
        // URL após login bem-sucedido.
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        // Verifique se não temos usuários no banco de dados. Se sim, crie
        // o usuário 'Admin'.
        $this->userManager->createAdminUserIfNotExists();

        // Criar formulário de login
        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        // Armazena o status de login.
        $isLoginError = false;

        // Verifica se o usuário enviou o formulário
        if ($this->getRequest()->isPost()) {

            // Preencher o formulário com dados POST
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validar formulário
            if ($form->isValid()) {

                // Obtenha dados filtrados e validados
                $data = $form->getData();

                // Executa uma tentativa de login.
                $result = $this->authManager->login($data['email'],
                    $data['password'], $data['remember_me']);

                // Verifique o resultado.
                if ($result->getCode() == Result::SUCCESS) {

                    // Obtem URL de redirecionamento.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {
                        // A verificação abaixo é para prevenir um possível ataque de redirecionamento
                        // (se alguém tentar redirecionar o usuário para outro domínio).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }

                    // Se o URL de redirecionamento for fornecido, redirecione o usuário para esse URL;
                    // caso contrário, redireciona para a página inicial.
                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    /**
     * A ação "logout" executa a operação de logout.
     */
    public function logoutAction()
    {
        $this->authManager->logout();

        return $this->redirect()->toRoute('login');
    }
}
