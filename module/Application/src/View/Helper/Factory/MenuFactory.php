<?php
namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\View\Helper\Menu;
use Application\Service\NavManager;

/**
 * Esta é a fábrica para o auxiliar de visualização do Menu. Seu objetivo é instanciar o
 * itens de menu auxiliar e init.
 */
class MenuFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $navManager = $container->get(NavManager::class);
        
        // Get menu items.
        $items = $navManager->getMenuItems();
        
        // Instantiate the helper.
        return new Menu($items);
    }
}

