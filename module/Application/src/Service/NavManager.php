<?php

namespace Application\Service;

/**
 * Este serviço é responsável por determinar quais itens devem estar no menu principal.
 * Os itens podem ser diferentes dependendo se o usuário é autenticado ou não.
 */
class NavManager
{
    /**
     * Serviço de autenticação.
     * @var Zend\Authentication\Authentication
     */
    private $authService;

    /**
     * Assistente de visualização de URL.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;

    /**
     * Constrói o serviço.
     */
    public function __construct($authService, $urlHelper)
    {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Este método retorna itens de menu dependendo se o usuário está conectado ou não.
     */
    public function getMenuItems()
    {
        $url = $this->urlHelper;
        $items = [];

        $items[] = [
            'id' => 'home',
            'label' => 'inicio',
            'link' => $url('home')
        ];

        $items[] = [
            'id' => 'about',
            'label' => 'Sobre',
            'link' => $url('about')
        ];

        // Exibe o item de menu "Login" apenas para usuários não autorizados. Por outro lado,
        // exibe os itens de menu "Admin" e "Logout" apenas para usuários autorizados.
        if (!$this->authService->hasIdentity()) {
            $items[] = [
                'id' => 'login',
                'label' => 'Registrar-se',
                'link' => $url('login'),
                'float' => 'right'
            ];
        } else {

            $items[] = [
                'id' => 'admin',
                'label' => 'Admin',
                'dropdown' => [
                    [
                        'id' => 'users',
                        'label' => 'Gerenciar Usuários',
                        'link' => $url('users')
                    ]
                ]
            ];

            $items[] = [
                'id' => 'logout',
                'label' => $this->authService->getIdentity(),
                'float' => 'right',
                'dropdown' => [
                    [
                        'id' => 'settings',
                        'label' => 'Configurações',
                        'link' => $url('application', ['action' => 'settings'])
                    ],
                    [
                        'id' => 'logout',
                        'label' => 'Sair',
                        'link' => $url('logout')
                    ],
                ]
            ];
        }

        return $items;
    }
}


