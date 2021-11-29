<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * This is configuration for the ZendDeveloperTools development toolbar.
 *
 * It will be enabled when you enable development mode.
 */
return [
    'zenddevelopertools' => [
        /**
         * Configurações gerais do Profiler
         */
        'profiler' => [
            /**
             * Habilita ou desabilita o criador de perfil.
             *
             * Expects: bool
             * Default: true
             */
            'enabled' => true,

            /**
             * Habilita ou desabilita o modo estrito. Se o modo estrito estiver ativado, qualquer erro lançará uma exceção,
             * caso contrário, todos os erros serão adicionados ao relatório (e mostrados na barra de ferramentas).
             *
             * Expera: bool
             * Padrão: true
             */
            'strict' => true,

            /**
             * Se ativado, o criador de perfil tenta liberar o conteúdo antes de começar a coletar dados. Esta opção
             * será ignorado se a barra de ferramentas estiver habilitada.
             *
             * Observação: o ouvinte de liberação escuta o evento MvcEvent :: EVENT_FINISH com uma prioridade de -9400. Você tem
             * para desabilitar esta função se você deseja modificar a saída com uma prioridade mais baixa.
             *
             * Espera: bool
             * Padrão: false
             */
            'flush_early' => false,

            /**
             * O diretório de cache é usado na verificação de versão e para cada tipo de armazenamento que grava no disco.
             * Nota: O valor padrão assume que o diretório de trabalho atual é a raiz do aplicativo.
             *
             * Espera: string
             * Padrão: 'data/cache'
             */
            'cache_dir' => 'data/cache',

            /**
             * Se uma correspondência for definida, o criador de perfil será desabilitado se a solicitação não corresponder ao padrão.
             *
             * Exemplo: 'matcher' => array ('ip' => '127.0.0.1')
             * OU
             * 'matcher' => array ('url' => array ('path' => '/admin')
             * Nota: O matcher ainda não foi implementado!
             **/
            'matcher' => [],

            /**
             * Contém uma lista com todos os coletores que o criador de perfil deve executar. Zend Developer Tools vem com
             * 'db' (Zend \ Db), 'hora', 'evento', 'memória', 'exceção', 'solicitação' e 'correio' (Zend \ Mail). Se você deseja
             * desabilite um coletor padrão, simplesmente defina o valor como nulo ou falso.
             *
             * Exemplo: 'coletores' => array ('db' => null)
             * Espera: array
             */
            'collectors' => [],
        ],
        'events' => [
            /**
             * Defina como true para habilitar o log em nível de evento para coletores que irão suportá-lo. Isso ativa um caractere curinga
             * ouvinte no gerenciador de eventos compartilhados que permitirá a criação de perfil de eventos definidos pelo usuário, bem como o
             * eventos ZF integrados.
             *
             * Espera: bool
             * Padrão: false
             */
            'enabled' => true,

            /**
             * Contém uma lista com todos os coletores de nível de evento que devem ser executados. Zend Developer Tools vem com 'tempo'
             * e 'memória'. Se você deseja desabilitar um coletor padrão, simplesmente defina o valor como nulo ou falso.
             *
             * Exemplo: 'coletores' => matriz ('memória' => null)
             * Espera: array
             */
            'collectors' => [],

            /**
             * Contém identificadores de eventos usados com o ouvinte de eventos. O padrão Zend Developer Tools para ouvir todos
             * eventos. Se você deseja desativar o identificador completo padrão, basta definir o valor como nulo ou
             * false.
             *
             * Exemplo: 'identificadores' => array ('all' => null, 'dispatchable' => 'Zend \ Stdlib \ DispatchableInterface')
             * Espera: array
             */
            'identifiers' => [],
        ],
        /**
         * Configurações gerais da barra de ferramentas
         */
        'toolbar' => [
            /**
             * Habilita ou desabilita a Barra de Ferramentas.
             *
             * Espera: bool
             * Padrão: false
             */
            'enabled' => true,

            /**
             * Se ativado, todos os coletores vazios serão ocultados.
             *
             * Espera: bool
             * Padrão: false
             */
            'auto_hide' => false,

            /**
             * A posição da barra de ferramentas.
             *
             * Espera: string ('inferior' ou 'superior')
             * Padrão: inferior
             */
            'position' => 'bottom',

            /**
             * Se habilitada, a barra de ferramentas irá verificar se a sua versão atual do Zend Framework está atualizada.
             * Obs: A verificação ocorrerá apenas uma vez a cada hora.
             *
             * Espera: bool
             * Padrão: false
             */
            'version_check' => false,

            /**
             * Contém uma lista com todos os modelos da barra de ferramentas do coletor. O nome da chave do array deve ser igual ao nome
             * do coletor.
             *
             * Exemplo: 'profiler' => array (
             * 'coletores' => array (
             * // My_Collector_Example :: getName () -> mycollector
             * 'MyCollector' => 'My_Collector_Example',
             *)
             *),
             * 'barra de ferramentas' => array (
             * 'entradas' => matriz (
             * 'meu coletor' => 'exemplo / barra de ferramentas / meu-coletor',
             *)
             *),
             * Espera: array
             */
            'entries' => [],
        ],
    ],
];
