<?php

namespace User\Form;

use User\Entity\Rooms;
use User\Validator\UserExistsValidator;
use Zend\Form\Element;
use Zend\Form\Element\DateTimeSelect;
use Zend\Form\Form;
use Zend\Form\Element\Select;



/**
 * Este formulário é usado para coletar o e-mail, nome completo, senha e status do usuário. A forma
 * pode funcionar em dois cenários - 'criar' e 'atualizar'. No cenário 'criar', o usuário
 * insere a senha, no cenário 'atualizar' ele não insere a senha.
 */
class ScheduleForm extends Form
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
     * Entidade atual.
     * @var User\Entity\ScheduleRooms
     */
    private $ScheduleRooms = null;

    /**
     * Construtor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $ScheduleRooms = null)
    {
        // Definir o nome do formulário
        parent::__construct('room-form');

        // Defina o método POST para este formulário
        $this->setAttribute('method', 'post');

        // Salve os parâmetros para uso interno.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->ScheduleRooms = $ScheduleRooms;
        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * Este método adiciona elementos ao formulário (campos de entrada e botão de envio).
     */
    protected function addElements()
    {
        // Adicionar campo "nome somente na criação"

            $this->add([
                'type' => 'text',
                'name' => 'room_name',
                'options' => [
                    'label' => 'Nome',
                ],
            ]);
        // Adicionar campo "área"
        $this->add([
            'type' => 'text',
            'name' => 'area',
            'options' => [
                'label' => 'Area m2',
            ],
        ]);

        $datetimeselect = new DateTimeSelect;
        $datetimeselect->setName('appointment-date-time');
        $datetimeselect->setAttributes(array(
            'min'  => '2010-01-01T00:00:00Z',
            'max'  => '2020-01-01T00:00:00Z',
            'step' => '1', // minutes; default step interval is 1 min
        ));
        $datetimeselect->setOptions(array(
            'label' => 'Data de agendamento',
            'format' => 'Y-m-d H:i:s'));
        $datetimeselect->setValue(date('Y-m-d H:i:s'));
        $this->add($datetimeselect);








        $data=$this->entityManager->getRepository(Rooms::class)->returnAllRooms();
       // var_dump($data);
        $roomlist = [];
        foreach ($data as $room){
            $roomlist[$room['idroom']] = $room['room_name'];
        }
        $this->add(array(
            'name' => 'rooms',
            'type' => Select::class,
            'options' => [
                'label' => 'Selecione a Sala?',
                'value_options' => $roomlist,
                'empty_option' => 'Selecione uma opção'
            ],
            'attributes' => [
                'id' => 'select',
                'class' => 'form-control'
            ],
        ));



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
        $query = $this->entityManager->getRepository(Rooms::class)->findAllRooms();
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