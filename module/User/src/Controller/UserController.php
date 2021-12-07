<?php

namespace User\Controller;

use Application\Entity\Post;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use User\Entity\User;
use User\Form\PasswordChangeForm;
use User\Form\PasswordResetForm;
use User\Form\UserForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Este controlador é responsável pelo gerenciamento de usuários (adição, edição,
 * visualização de usuários e alteração de senha de usuário).
 */
class UserController extends AbstractActionController
{
    /**
     * Gerente de entidade.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Gerenciador de usuário.
     * @var User\Service\UserManager
     */
    private $userManager;

    /**
     * Construtor.
     */
    public function __construct($entityManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    /**
     * Esta é a ação "índice" padrão do controlador. Ele exibe o
     * lista de usuários.
     */
    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(User::class)
            ->findAllUsers();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'users' => $paginator
        ]);
    }

    /**
     * Esta ação exibe uma página que permite adicionar um novo usuário.
     */
    public function addAction()
    {
        // Criar formulário de usuário
        $form = new UserForm('create', $this->entityManager);

        // Verifique se o usuário enviou o formulário
        if ($this->getRequest()->isPost()) {

            // Preencher o formulário com dados POST
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validar formulário
            if ($form->isValid()) {

                // Obtenha dados filtrados e validados
                $data = $form->getData();

                // Adicionar usuário.
                $user = $this->userManager->addUser($data);

                // Redirecionar para a página "visualizar"
                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * A ação "visualizar" exibe uma página que permite visualizar os detalhes do usuário.
     */
    public function viewAction()
    {
        // verifica se o parametro id foi associado
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Encontre um usuário com tal ID.
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        //Se o usuário for null significa que ele não está logado
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'user' => $user
        ]);
    }

    /**
     * A ação "editar" exibe uma página que permite editar o usuário.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Criar formulário de usuário
        $form = new UserForm('update', $this->entityManager, $user);


        // Verifique se o usuário enviou o formulário
        if ($this->getRequest()->isPost()) {

            // Preencher o formulário com dados POST
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validar formulário
            if ($form->isValid()) {

                // Obter dados filtrados e validados
                $data = $form->getData();

                // Atualize o usuário.
                $this->userManager->updateUser($user, $data);

                // Redirecionar para a página "visualizar"
                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        } else {
            $form->setData(array(
                'full_name' => $user->getFullName(),
                'email' => $user->getEmail(),
                'status' => $user->getStatus(),
            ));
        }

        return new ViewModel(array(
            'user' => $user,
            'form' => $form
        ));
    }

    /**
     * Esta ação exibe uma página que permite alterar a senha do usuário.
     */
    public function changePasswordAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Criar formulário "alterar senha"
        $form = new PasswordChangeForm('change');

        // Verifique se o usuário enviou o formulário
        if ($this->getRequest()->isPost()) {

            // Preencher o formulário com dados POST
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validar formulário
            if ($form->isValid()) {

                // Obter dados filtrados e validados
                $data = $form->getData();

                // Tente alterar a senha.
                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage(
                        'Desculpe, a senha antiga está incorreta. Não foi possível definir a nova senha.');
                } else {
                    $this->flashMessenger()->addSuccessMessage(
                        'Senha alterado com sucesso.');
                }

                // Redirecionar para a página "visualizar"
                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        }

        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }

    /**
     * Esta ação exibe a página "Redefinir senha".
     */
    public function resetPasswordAction()
    {
        // Criar formulário
        $form = new PasswordResetForm();

        // Verifique se o usuário enviou o formulário
        if ($this->getRequest()->isPost()) {

            // Preencher o formulário com dados POST
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validar formulário
            if ($form->isValid()) {

                // Procure o usuário com esse e-mail.
                $user = $this->entityManager->getRepository(User::class)
                    ->findOneByEmail($data['email']);

                if ($user != null && $user->getStatus() == User::STATUS_ACTIVE) {
                    // Gere uma nova senha para o usuário e envia um e-mail
                    // notificação sobre isso.
                    $this->userManager->generatePasswordResetToken($user);

                    // Redirecionar para a página de "mensagem"
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'sent']);
                } else {
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'invalid-email']);
                }
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * Esta ação exibe uma página de mensagem informativa.
     * Por exemplo, "Sua senha foi redefinida" e assim por diante.
     */
    public function messageAction()
    {
        // Pega o ID da mensagem da rota.
        $id = (string)$this->params()->fromRoute('id');

        // Valide o argumento de entrada.
        if ($id != 'invalid-email' && $id != 'sent' && $id != 'set' && $id != 'failed') {
            throw new \Exception('ID de mensagem especificado é inválido');
        }

        return new ViewModel([
            'id' => $id
        ]);
    }

    /**
     * Esta ação exibe a página "Redefinir senha".
     */
    public function setPasswordAction()
    {
        $email = $this->params()->fromQuery('email', null);
        $token = $this->params()->fromQuery('token', null);

        // Validar o comprimento do token
        if ($token != null && (!is_string($token) || strlen($token) != 32)) {
            throw new \Exception('Tipo ou comprimento de token inválido');
        }

        if ($token === null ||
            !$this->userManager->validatePasswordResetToken($email, $token)) {
            return $this->redirect()->toRoute('users',
                ['action' => 'message', 'id' => 'failed']);
        }

        // Criar formulário
        $form = new PasswordChangeForm('reset');

        // Verifique se o usuário enviou o formulário
        if ($this->getRequest()->isPost()) {

            // Preencher o formulário com dados POST
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validar formulário
            if ($form->isValid()) {

                $data = $form->getData();

                // Defina uma nova senha para o usuário.
                if ($this->userManager->setNewPasswordByToken($email, $token, $data['new_password'])) {

                    // Redirecionar para a página de "mensagem"
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'set']);
                } else {
                    // Redirecionar para a página de "mensagem"
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'failed']);
                }
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }
}


