<?php

namespace User;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'reset-password' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/reset-password',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'resetPassword',
                    ],
                ],
            ],
            'set-password' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/set-password',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'setPassword',
                    ],
                ],
            ],
            'users' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/users[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'rooms' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/rooms[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'rooms',
                    ],
                ],
            ],

            'schedule'  => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/schedule[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'schedule',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
        ],
    ],
    // Registramos plug-ins de controlador fornecidos por módulo nesta chave.
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\CurrentUserPlugin::class => Controller\Plugin\Factory\CurrentUserPluginFactory::class,
        ],
        'aliases' => [
            'currentUser' => Controller\Plugin\CurrentUserPlugin::class,
        ],
    ],
    // A chave 'access_filter' é usada pelo módulo do usuário para restringir ou permitir
    // acesso a certas ações do controlador para visitantes não autorizados.
    'access_filter' => [
        'controllers' => [
            Controller\UserController::class => [
                // Dar acesso às ações "resetPassword", "message", "setPassword" 'rooms','deleteroom','editroom', 'viewroom','addroom','rooms
                // para ninguém.
                ['actions' => ['resetPassword', 'message', 'setPassword'], 'allow' => '*'],
                // Dê acesso às ações "index", "add", "edit", "view", "changePassword" apenas para usuários autorizados.
                ['actions' => ['index', 'add', 'edit', 'view', 'changePassword','rooms','deleteroom','editroom', 'viewroom','addroom','rooms','schedule'], 'allow' => '@']
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\RoomsManager::class => Service\Factory\RoomsManagerFactory::class,
            Service\ScheduleRoomsManager::class => Service\Factory\ScheduleRoomsManagerFactory::class,


        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    // Registramos assistentes de visualização fornecidos por módulo sob esta chave.
    'view_helpers' => [
        'factories' => [
            View\Helper\CurrentUser::class => View\Helper\Factory\CurrentUserFactory::class,
        ],
        'aliases' => [
            'currentUser' => View\Helper\CurrentUser::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
