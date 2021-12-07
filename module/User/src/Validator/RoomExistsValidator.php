<?php

namespace User\Validator;

use User\Entity\Rooms;
use Zend\Validator\AbstractValidator;

/**
 * Esta classe validadora é projetada para verificar se há um usuário existente
 * com esse e-mail.
 */
class RoomExistsValidator extends AbstractValidator
{
    const NOT_SCALAR = 'notScalar';

    // IDs de mensagem de falha de validação.
    const ROOM_EXISTS = 'roomExists';
    /**
     * Opções de validador disponíveis.
     * @var array
     */
    protected $options = array(
        'entityManager' => null,
        'room' => null
    );
    /**
     * Mensagens de falha de validação.
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_SCALAR => "O Nome da Sala deve ser um valor escalar",
        self::ROOM_EXISTS => "Já existe outra sala Com este nome"
    );

    /**
     * Construtor.
     */
    public function __construct($options = null)
    {
        // Defina as opções de filtro (se fornecidas).
        if (is_array($options)) {
            if (isset($options['entityManager']))
                $this->options['entityManager'] = $options['entityManager'];
            if (isset($options['room']))
                $this->options['room'] = $options['user'];
        }

        // Chame o construtor da classe pai
        parent::__construct($options);
    }

    /**
     * Verifique se o usuário existe.
     */
    public function isValid($value)
    {
        if (!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        // Obtenha o gerenciador de entidade Doctrine.
        $entityManager = $this->options['entityManager'];

        $user = $entityManager->getRepository(Rooms::class)->findOneBy(['room_name'=>$value]);

        if ($this->options['room'] == null) {
            $isValid = ($user == null);
        } else {
            if ($this->options['room']->getEmail() != $value && $user != null)
                $isValid = false;
            else
                $isValid = true;
        }

        // Se houver um erro, defina a mensagem de erro.
        if (!$isValid) {
            $this->error(self::ROOM_EXISTS);
        }

        // Retorna o resultado da validação.
        return $isValid;
    }
}

