<?php

namespace User\Form;

use Zend\Form\Form;

/**
 * Este formulário é usado ao alterar a senha do usuário (para coletar a senha antiga do usuário
 * e nova senha) ou ao redefinir a senha do usuário (quando o usuário esqueceu sua senha).
 */
class PasswordChangeForm extends Form
{
    // Pode haver dois cenários - 'alterar' ou 'redefinir'.
    private $scenario;

    /**
     * Construtor.
     * @param string $scenario Ou 'alterar' ou 'redefinir'.
     */
    public function __construct($scenario)
    {
        // Definir o nome do formulário
        parent::__construct('password-change-form');

        $this->scenario = $scenario;

        // Defina o método POST para este formulário
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * Este método adiciona elementos ao formulário (campos de entrada e botão de envio).
     */
    protected function addElements()
    {
        // Se o cenário for 'alterar', não solicitamos a senha antiga.
        if ($this->scenario == 'change') {

            // Adicionar campo "senha_antiga"
            $this->add([
                'type' => 'password',
                'name' => 'old_password',
                'options' => [
                    'label' => 'Senha Atual',
                ],
            ]);
        }

        // Adicionar campo "new_password"
        $this->add([
            'type' => 'password',
            'name' => 'new_password',
            'options' => [
                'label' => 'Nova Senha',
            ],
        ]);

        // Adicionar campo "confirm_new_password"
        $this->add([
            'type' => 'password',
            'name' => 'confirm_new_password',
            'options' => [
                'label' => 'Confirmar nova senha',
            ],
        ]);

        // Adicione o campo CSRF
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        // Adicione o botão Enviar
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Alterar Senha'
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

        if ($this->scenario == 'change') {

            // Adicionar entrada para o campo "senha_antiga"
            $inputFilter->add([
                'name' => 'old_password',
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
        }

        // Adicionar entrada para o campo "new_password"
        $inputFilter->add([
            'name' => 'new_password',
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

        // Adicionar entrada para o campo "confirm_new_password"
        $inputFilter->add([
            'name' => 'confirm_new_password',
            'required' => true,
            'filters' => [
            ],
            'validators' => [
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'new_password',
                    ],
                ],
            ],
        ]);
    }
}

