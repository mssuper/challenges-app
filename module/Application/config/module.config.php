<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'about' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/about',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'about',
                    ],
                ],
            ],            
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    // A chave 'access_filter' é usada pelo módulo do usuário para restringir ou permitir
    // acesso a certas ações do controlador para visitantes não autorizados.
    'access_filter' => [
        'options' => [
            // O filtro de acesso pode funcionar em 'restritivo' (recomendado) ou 'permissivo'
            // modo. No modo restritivo, todas as ações do controlador devem ser listadas explicitamente
            // sob a chave de configuração 'access_filter', e o acesso é negado a qualquer não listado
            // ação para usuários não logados. No modo permissivo, se uma ação não estiver listada
            // sob a chave 'access_filter', o acesso a ela é permitido a qualquer pessoa (mesmo para
            // usuários não logados. O modo restritivo é mais seguro e recomendado para uso.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Permitir que qualquer pessoa visite as ações "índice" e "sobre"
                ['actions' => ['index', 'about'], 'allow' => '*'],
                // Permitir que usuários autorizados visitem a ação "configurações"
                ['actions' => ['settings'], 'allow' => '@']
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            Service\NavManager::class => Service\Factory\NavManagerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => View\Helper\Factory\MenuFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    // A chave a seguir permite definir um estilo personalizado para o assistente de visualização do FlashMessenger.
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<div%s><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ]
    ],   
];
