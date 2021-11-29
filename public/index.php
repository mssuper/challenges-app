<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

/**
 * Isso facilita nossa vida ao lidar com caminhos. Tudo é relativo
 * para a raiz do aplicativo agora.
 */
chdir(dirname(__DIR__));

// * Isso facilita nossa vida ao lidar com caminhos. Tudo é relativo para a raiz do aplicativo agora.
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Autoloading do Composer
include __DIR__ . '/../vendor/autoload.php';

if (!class_exists(Application::class)) {
    throw new RuntimeException(
        "Não é possível carregar o aplicativo.\n"
        . "- Digite `composer install` se você estiver desenvolvendo localmente.\n"
        . "- Digite `vagrant ssh -c 'composer install'` se você estiver usando o Vagrant.\n"
        . "- Digite `docker-compose run zf composer install` se estiver usando o Docker.\n"
    );
}

// Recuperar configuração
$appConfig = require __DIR__ . '/../config/application.config.php';
if (file_exists(__DIR__ . '/../config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/../config/development.config.php');
}

// Execute o aplicativo!
Application::init($appConfig)->run();
