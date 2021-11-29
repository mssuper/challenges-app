<?php
/**
 * Se você precisa de um sistema específico do ambiente ou configuração de aplicativo,
 * há um exemplo na documentação
 * @see http://framework.zend.com/manual/current/en/tutorials/config.advanced.html#environment-specific-system-configuration
 * @see http://framework.zend.com/manual/current/en/tutorials/config.advanced.html#environment-specific-application-configuration
 */
return [
    // Recupere a lista de módulos usados neste aplicativo.
    'modules' => require __DIR__ . '/modules.config.php',

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => [
        // Deve ser uma matriz de caminhos nos quais residem os módulos.
        // Se uma chave de string for fornecida, o ouvinte irá considerar que um módulo
        // namespace, o valor dessa chave o caminho específico para o do módulo
        // Classe do módulo.
        'module_paths' => [
            './module',
            './vendor',
        ],

        // Uma matriz de caminhos a partir dos quais os arquivos de configuração globais após
        // módulos são carregados. Eles efetivamente substituem a configuração
        // fornecido pelos próprios módulos. Os caminhos podem usar a notação GLOB_BRACE.
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],

        // Se deve ou não habilitar um cache de configuração.
        // Se habilitado, a configuração mesclada será armazenada em cache e usada em
        // solicitações subsequentes.
        'config_cache_enabled' => true,

        // A chave usada para criar o nome do arquivo de cache de configuração.
        'config_cache_key' => 'application.config.cache',

        // Se deve ou não habilitar um cache de mapa de classe de módulo.
        // Se habilitado, cria um cache de mapa de classe de módulo que será usado
        // por em solicitações futuras, para reduzir o processo de carregamento automático.
        'module_map_cache_enabled' => true,

        // A chave usada para criar o nome do arquivo de cache do mapa de classe.
        'module_map_cache_key' => 'application.module.cache',

        // O caminho no qual armazenar em cache a configuração mesclada.
        'cache_dir' => 'data/cache/',

        // Se deve ou não habilitar a verificação de dependência de módulos.
        // Habilitado por padrão, evita o uso de módulos que dependem de outros módulos
        // que não foram carregados.
        // 'check_dependencies' => true,
    ],

    // Usado para criar um gerenciador de serviço próprio. Pode conter uma ou mais matrizes filho.
    // 'service_listener_options' => [
    // [
    // 'service_manager' => $ stringServiceManagerName,
    // 'config_key' => $ stringConfigKey,
    // 'interface' => $ stringOptionalInterface,
    // 'method' => $ stringRequiredMethodName,
    //],
    //],

    // Configuração inicial com a qual propagar o ServiceManager.
    // Deve ser compatível com Zend \ ServiceManager \ Config.
    // 'service_manager' => [],
];
