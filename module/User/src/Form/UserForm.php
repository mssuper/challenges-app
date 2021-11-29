<?php

namespace User\Form;

use User\Validator\UserExistsValidator;
use Zend\Form\Form;

/**
 * Este formulário é usado para coletar o e-mail, nome completo, senha e status do usuário. A forma
 * pode funcionar em dois cenários - 'criar' e 'atualizar'. No cenário 'criar', o usuário
 * insere a senha, no cenário 'atualizar' ele não insere a senha.
 */
class UserForm extends Form
{
    /**
     * Cenário ('criar' ou 'atualizar').
     * @var string
     */
    private $scenario;

    /**
     * Gerenciador de entidade.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Usuário atual.
     * @var User\Entity\User
     */
    private $user = null;

    /**
     * Construtor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $user = null)
    {
        // Definir o nome do formulário
        parent::__construct('user-form');

        // Defina o método POST para este formulário
        $this->setAttribute('method', 'post');

        // Salve os parâmetros para uso interno.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->user = $user;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * Este método adiciona elementos ao formulário (campos de entrada e botão de envio).
     */
    protected function addElements()
    {
        // Adicionar campo "email"
        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
            ],
        ]);

        // Adicionar campo "full_name"
        $this->add([
            'type' => 'text',
            'name' => 'full_name',
            'options' => [
                'label' => 'Full Name',
            ],
        ]);

        if ($this->scenario == 'create') {

            // Adicionar campo "senha"
            $this->add([
                'type' => 'password',
                'name' => 'password',
                'options' => [
                    'label' => 'Password',
                ],
            ]);

            // Adicionar campo "confirm_password"
            $this->add([
                'type' => 'password',
                'name' => 'confirm_password',
                'options' => [
                    'label' => 'Confirm password',
                ],
            ]);
        }

        // Adicionar campo "status"
        $this->add([
            'type' => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    1 => 'Active',
                    2 => 'Retired',
                ]
            ],
        ]);

        // Adicione o botão Enviar
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create'
            ],
        ]);
    }

    /**
     * Este método cria um filtro de entrada (usado para filtragem / validação de formulário).
     */
    private function addInputFilter()
    {
        // Criar filtro de entrada principal
        $inputFilter = $this->getInputFilter();

        // Adicionar entrada para o campo "email"
        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
                [
                    'name' => UserExistsValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'user' => $this->user
                    ],
                ],
            ],
        ]);

        // Adicionar entrada para o campo "full_name"
        $inputFilter->add([
            'name' => 'full_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512
                    ],
                ],
            ],
        ]);

        if ($this->scenario == 'create') {

            // Adicionar entrada para o campo "senha"
            $inputFilter->add([
                'name' => 'password',
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);

            // Adicionar entrada para o campo "confirm_password"
            $inputFilter->add([
                'name' => 'confirm_password',
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ],
            ]);
        }

        // Adicionar entrada para o campo "status"
        $inputFilter->add([
            'name' => 'status',
            'required' => true,
            'filters' => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                ['name' => 'InArray', 'options' => ['haystack' => [1, 2]]]
            ],
        ]);
    }
}