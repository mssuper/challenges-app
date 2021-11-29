<?php
namespace User\Service;

use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
* Este serviço é responsável por adicionar / editar usuários
* e alteração da senha do usuário.
**/
class UserManager
{
    /**
     * * Gerente de entidade Doctrine.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
    /**
     * Renderizador de template PHP.
     * @var type 
     */
    private $viewRenderer;
    
    /**
     * Configuração do aplicativo.
     * @var type 
     */
    private $config;
    
    /**
     * Constrói o serviço.
     */
    public function __construct($entityManager, $viewRenderer, $config) 
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }
    
    /**
     * Este método adiciona um novo usuário.
     */
    public function addUser($data) 
    {
        // Não permite vários usuários com o mesmo endereço de e-mail.
        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }

        // Cria uma nova entidade de usuário.
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);

        // Criptografar a senha e armazená-la no estado criptografado.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);        
        $user->setPassword($passwordHash);
        
        $user->setStatus($data['status']);
        
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);

        // Adicione a entidade ao gerenciador de entidades.
        $this->entityManager->persist($user);

        // Aplicar mudanças ao banco de dados.
        $this->entityManager->flush();
        
        return $user;
    }
    
    /**
     * Este método atualiza os dados de um usuário existente.
     */
    public function updateUser($user, $data) 
    {
        // Não permite alterar o e-mail do usuário se outro usuário com esse e-mail já existir.
        if($user->getEmail()!=$data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
        }
        
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);        
        $user->setStatus($data['status']);        
        
        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }
    
    /**
     * Este método verifica se pelo menos um usuário apresenta, e se não, cria
     * Usuário 'Admin' com e-mail 'admin@example.com' e senha 'Secur1ty'.
     */
    public function createAdminUserIfNotExists()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if ($user==null) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setFullName('Admin');
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create('Secur1ty');        
            $user->setPassword($passwordHash);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setDateCreated(date('Y-m-d H:i:s'));
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
    
    /**
     * Verifica se um usuário ativo com determinado endereço de e-mail já existe no banco de dados.
     */
    public function checkUserExists($email) {
        
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($email);
        
        return $user !== null;
    }
    
    /**
     * Verifica se a senha fornecida está correta.
     */
    public function validatePassword($user, $password) 
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();
        
        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Gera um token de redefinição de senha para o usuário. Este token é então armazenado no banco de dados e
     * enviado para o endereço de e-mail do usuário. Quando o usuário clica no link da mensagem de e-mail, ele é
     * direcionado para a página Definir senha.
     */
    public function generatePasswordResetToken($user)
    {
        if ($user->getStatus() != User::STATUS_ACTIVE) {
            throw new \Exception('Cannot generate password reset token for inactive user ' . $user->getEmail());
        }

        // Gere um token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);

        // Criptografe o token antes de armazená-lo no banco de dados.
        $bcrypt = new Bcrypt();
        $tokenHash = $bcrypt->create($token);

        // Salvar token no banco de dados
        $user->setPasswordResetToken($tokenHash);

        // Salve a data de criação do token no banco de dados.
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);

        // Aplicar mudanças ao banco de dados.
        $this->entityManager->flush();

        // Envie um email para o usuário.
        $subject = 'Password Reset';
            
        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token . "&email=" . $user->getEmail();

        // Produzir HTML do e-mail de redefinição de senha
        $bodyHtml = $this->viewRenderer->render(
                'user/email/reset-password-email',
                [
                    'passwordResetUrl' => $passwordResetUrl,
                ]);
        
        $html = new MimePart($bodyHtml);
        $html->type = "text/html";
        
        $body = new MimeMessage();
        $body->addPart($html);
        
        $mail = new Mail\Message();
        $mail->setEncoding('UTF-8');
        $mail->setBody($body);
        $mail->setFrom('no-reply@example.com', 'User Demo');
        $mail->addTo($user->getEmail(), $user->getFullName());
        $mail->setSubject($subject);
        
        // Setup SMTP transport
        $transport = new SmtpTransport();
        $options   = new SmtpOptions($this->config['smtp']);
        $transport->setOptions($options);

        $transport->send($mail);
    }
    
    /**
     * Verifica se o token de redefinição de senha fornecido é válido.
     */
    public function validatePasswordResetToken($email, $passwordResetToken)
    {
        // Encontre o usuário por e-mail.
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($email);
        
        if($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Verifique se o hash do token corresponde ao hash do token em nosso banco de dados.
        $bcrypt = new Bcrypt();
        $tokenHash = $user->getPasswordResetToken();
        
        if (!$bcrypt->verify($passwordResetToken, $tokenHash)) {
            return false; // mismatch
        }

        // Verifique se o token foi criado há não muito tempo.
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);
        
        $currentDate = strtotime('now');
        
        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false; // expired
        }
        
        return true;
    }
    
    /**
     * Este método define uma nova senha por token de redefinição de senha.
     */
    public function setNewPasswordByToken($email, $passwordResetToken, $newPassword)
    {
        if (!$this->validatePasswordResetToken($email, $passwordResetToken)) {
           return false; 
        }

        // Encontre o usuário com o e-mail fornecido.
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($email);
        
        if ($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Definir nova senha para o usuário
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);        
        $user->setPassword($passwordHash);

        // Remover token de redefinição de senha
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);
        
        $this->entityManager->flush();
        
        return true;
    }
    
    /**
     * Este método é usado para alterar a senha de um determinado usuário. Para alterar a senha,
     * é preciso saber a senha antiga.
     */
    public function changePassword($user, $data)
    {
        $oldPassword = $data['old_password'];

        // Verifique se a senha antiga está correta
        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }                
        
        $newPassword = $data['new_password'];

        // Verifique o comprimento da senha
        if (strlen($newPassword)<6 || strlen($newPassword)>64) {
            return false;
        }

        // Definir nova senha para o usuário
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        // Aplicar mudanças
        $this->entityManager->flush();

        return true;
    }
}

