<?php

namespace User\Form;

use User\Validator\UserExistsValidator;
use Zend\Form\Form;

/**
 * Este formulário é usado para coletar o e-mail, nome completo, senha e status do usuário. A forma
 * pode funcionar em dois cenários - 'criar' e 'atualizar'. No cenário 'criar', o usuário
 * insere a senha, no cenário 'atualizar' ele não insere a senha.
 */
class RoomForm extends Form
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
     * @var User\Entity\Rooms
     */
    private $rooms = null;

    /**
     * Construtor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $rooms = null)
    {
        // Definir o nome do formulário
        parent::__construct('room-form');

        // Defina o método POST para este formulário
        $this->setAttribute('method', 'post');

        // Salve os parâmetros para uso interno.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->rooms = $rooms;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * Este método adiciona elementos ao formulário (campos de entrada e botão de envio).
     */
    protected function addElements()
    {
        // Adicionar campo "nome somente na criação"
        if ($this->scenario==='update') {
            $this->add([
                'type' => 'text',
                'name' => 'room_name',
                'attributes' => [
                        'readonly' => true,
                        ],
                'options' => [
                    'label' => 'Nome',
                ],
            ]);
        }else
        {
            $this->add([
                'type' => 'text',
                'name' => 'room_name',
                'options' => [
                    'label' => 'Nome',
                ],
            ]);
        }
        // Adicionar campo "área"
        $this->add([
            'type' => 'text',
            'name' => 'area',
            'options' => [
                'label' => 'Area m2',
            ],
        ]);



        // Adicionar campo "status"
        $this->add([
            'type' => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    1 => 'Active',
                    2 => 'Inativo',
                ]
            ],
        ]);

        // Adicione o botão Enviar
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Criar'
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
            'name' => 'area',
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
            ],
        ]);

        // Adicionar entrada para o campo "full_name"
        $inputFilter->add([
            'name' => 'room_name',
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
            ],
        ]);

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