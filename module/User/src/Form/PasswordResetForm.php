<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Este formulário é usado para coletar o endereço de e-mail do usuário (usado para recuperar a senha).
 */
class PasswordResetForm extends Form
{
    /**
     * Construtor.
     */
    public function __construct()
    {
        // Definir o nome do formulário
        parent::__construct('password-reset-form');

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
            'type'  => 'email',
            'name' => 'email',
            'options' => [
                'label' => 'Your E-mail',
            ],
        ]);

        // Adicione o campo CAPTCHA
        $this->add([
            'type' => 'captcha',
            'name' => 'captcha',
            'options' => [
                'label' => 'Human check',
                'captcha' => [
                    'class' => 'Image',
                    'imgDir' => 'public/img/captcha',
                    'suffix' => '.png',
                    'imgUrl' => '/img/captcha/',
                    'imgAlt' => 'CAPTCHA Image',
                    'font' => './data/font/thorne_shaded.ttf',
                    'fsize' => 24,
                    'width' => 350,
                    'height' => 100,
                    'expiration' => 600,
                    'dotNoiseLevel' => 40,
                    'lineNoiseLevel' => 3
                ],
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
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Reset Password',
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
                            'useMxCheck'    => false,                            
                        ],
                    ],
                ],
            ]);                     
    }        
}
