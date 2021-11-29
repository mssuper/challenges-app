<?php

namespace User\Service;

use User\Entity\User;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;

/**
 * Adaptador usado para autenticar o usuário. É necessário login e senha na entrada
 * e verifica no banco de dados se existe um usuário com tal login (email) e senha.
 * Se tal usuário existe, o serviço retorna sua identidade (e-mail). A identidade
 * é salvo na sessão e pode ser recuperado mais tarde com o assistente de visualização de identidade fornecido
 * por ZF3.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * User email.
     * @var string
     */
    private $email;

    /**
     * Sernha
     * @var string
     */
    private $password;

    /**
     * Gerenciador de entidade.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Construtor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Define o e-mail do usuário.
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Define a senha.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    /**
     * Executa uma tentativa de autenticação.
     */
    public function authenticate()
    {
        // Verifique no banco de dados se existe um usuário com tal e-mail.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($this->email);

        // Se não houver tal usuário, retorna o status 'Identidade não encontrada'.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        // Se o usuário com esse e-mail existe, precisamos verificar se ele está ativo ou retirado.
        // Não permite que usuários aposentados façam login.
        if ($user->getStatus() == User::STATUS_RETIRED) {
            return new Result(
                Result::FAILURE,
                null,
                ['User is retired.']);
        }

        // Agora precisamos calcular o hash com base na senha inserida pelo usuário e comparar
        // com o hash de senha armazenado no banco de dados.
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            // Excelente! O hash da senha corresponde. Retornar a identidade do usuário (e-mail) para ser
            // salvo na sessão para uso posterior.
            return new Result(
                Result::SUCCESS,
                $this->email,
                ['Authenticated successfully.']);
        }

        // Se a verificação da senha não passou, retornou o status de falha 'Credencial inválido'.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}


