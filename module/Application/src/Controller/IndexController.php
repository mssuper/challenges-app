<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Esta é a principal classe de controlador do aplicativo User Demo. Contém
 * ações em todo o site, como Home ou About.
 */
class IndexController extends AbstractActionController
{
    /**
     * Gerenciado de Entidade.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Construtor. Seu objetivo é injetar dependências no controlador.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Esta é a ação "índice" padrão do controlador. Ele exibe o
     * Pagina inicial.
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
    * Esta é a ação "índice" padrão do controlador. Ele exibe o
     * Pagina inicial.
     */
    public function aboutAction()
    {
        $appName = 'App-Challenge';
        $appDescription = 'Este é um demo para exercício e de como implementar PHP com Zend Framework 3';

        // Retorna variáveis ​ para visualizar o script com a ajuda do
        // Contêiner da variável ViewObject
        return new ViewModel([
            'appName' => $appName,
            'appDescription' => $appDescription
        ]);
    }

    /**
     * A ação "configurações" exibe as informações sobre o usuário conectado no momento.
     */
    public function settingsAction()
    {
        // Use o plugin do controlador CurrentUser para obter o usuário atual.
        $user = $this->currentUser();

        if ($user == null) {
            throw new \Exception('Não Logado');
        }

        return new ViewModel([
            'user' => $user
        ]);
    }
}

