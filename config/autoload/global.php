<?php
/**
 * Substituição de configuração global
 *
 * Você pode usar este arquivo para substituir os valores de configuração dos módulos, etc.
 * Você colocaria valores aqui que são agnósticos ao meio ambiente e não
 * sensível à segurança.
 *
 * @NOTA: Na prática, este arquivo normalmente será INCLUÍDO em sua fonte
 * controle, portanto, não inclua senhas ou outras informações confidenciais neste
 * Arquivo.
 */

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

return [
    // Configuração da sessão.
    'session_config' => [
        'cookie_lifetime' => 60 * 60 * 1, // O cookie da sessão irá expirar em 1 hora.
        'gc_maxlifetime' => 60 * 60 * 24 * 30, // Por quanto tempo armazenar os dados da sessão no servidor (por 1 mês).
    ],
    // Configuração do gerenciador de sessão.
    'session_manager' => [
        // Validadores de sessão (usados para segurança).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Configuração de armazenamento da sessão.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'doctrine' => [
        // configuração de migrações
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/Migrations',
                'name' => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table' => 'migrations',
            ],

        ],
        // Configuração da conexão do banco de dados.
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host' => 'localhost',
                    'user' => 'challenges-app',
                    'password' => 'FVN5jmJldbtTGgA7',
                    'dbname' => 'challenges-app',
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                    ]
                ],
            ],
        ],
    ],
];
