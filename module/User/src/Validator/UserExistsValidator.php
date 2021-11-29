<?php
namespace User\Validator;

use Zend\Validator\AbstractValidator;
use User\Entity\User;
/**
 * Esta classe validadora é projetada para verificar se há um usuário existente
 * com esse e-mail.
 */
class UserExistsValidator extends AbstractValidator 
{
    /**
     * Opções de validador disponíveis.
     * @var array
     */
    protected $options = array(
        'entityManager' => null,
        'user' => null
    );

    // IDs de mensagem de falha de validação.
    const NOT_SCALAR  = 'notScalar';
    const USER_EXISTS = 'userExists';
        
    /**
     * Mensagens de falha de validação.
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_SCALAR  => "The email must be a scalar value",
        self::USER_EXISTS  => "Another user with such an email already exists"        
    );
    
    /**
     * Construtor.
     */
    public function __construct($options = null) 
    {
        // Defina as opções de filtro (se fornecidas).
        if(is_array($options)) {            
            if(isset($options['entityManager']))
                $this->options['entityManager'] = $options['entityManager'];
            if(isset($options['user']))
                $this->options['user'] = $options['user'];
        }

        // Chame o construtor da classe pai
        parent::__construct($options);
    }
        
    /**
     * Verifique se o usuário existe.
     */
    public function isValid($value) 
    {
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false; 
        }

        // Obtenha o gerenciador de entidade Doctrine.
        $entityManager = $this->options['entityManager'];
        
        $user = $entityManager->getRepository(User::class)
                ->findOneByEmail($value);
        
        if($this->options['user']==null) {
            $isValid = ($user==null);
        } else {
            if($this->options['user']->getEmail()!=$value && $user!=null) 
                $isValid = false;
            else 
                $isValid = true;
        }

        // Se houver um erro, defina a mensagem de erro.
        if(!$isValid) {            
            $this->error(self::USER_EXISTS);            
        }

        // Retorna o resultado da validação.
        return $isValid;
    }
}

