<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user's login, password and 'Remember Me' flag.
 */
class LoginForm extends Form
{
    /**
     * Construtor.
     */
    public function __construct()
    {
        // Definir o nome do formulário
        parent::__construct('login-form');

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
        // Adicionar campo "email"
        $this->add([            
            'type'  => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Your E-mail',
            ],
        ]);

        // Adicionar campo "senha"
        $this->add([            
            'type'  => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        // Adicionar campo "lembrar-me"
        $this->add([            
            'type'  => 'checkbox',
            'name' => 'remember_me',
            'options' => [
                'label' => 'Remember me',
            ],
        ]);

        // Adicionar campo "redirect_url"
        $this->add([            
            'type'  => 'hidden',
            'name' => 'redirect_url'
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
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Sign in',
                'id' => 'submit',
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
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck' => false,                            
                        ],
                    ],
                ],
            ]);

        // Adicionar entrada para o campo "senha"
        $inputFilter->add([
                'name'     => 'password',
                'required' => true,
                'filters'  => [                    
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);

        // Adicionar entrada para o campo "lembrar_me"
        $inputFilter->add([
                'name'     => 'remember_me',
                'required' => false,
                'filters'  => [                    
                ],                
                'validators' => [
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => [0, 1],
                        ]
                    ],
                ],
            ]);

        // Adicionar entrada para o campo "redirect_url"
        $inputFilter->add([
                'name'     => 'redirect_url',
                'required' => false,
                'filters'  => [
                    ['name'=>'StringTrim']
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 0,
                            'max' => 2048
                        ]
                    ],
                ],
            ]);
    }        
}

