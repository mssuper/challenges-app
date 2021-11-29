<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Esta classe auxiliar de visualização exibe uma barra de menus.
 */
class Menu extends AbstractHelper 
{
    /**
     * array de itens de menu.
     * @var array 
     */
    protected $items = [];
    
    /**
     * ID do item ativo.
     * @var string  
     */
    protected $activeItemId = '';
    
    /**
     * Construtor.
     * @param array $items Itens do Menu.
     */
    public function __construct($items=[]) 
    {
        $this->items = $items;
    }
    
    /**
     * Define os itens do menu.
     * @param array $items Itens Menu.
     */
    public function setItems($items) 
    {
        $this->items = $items;
    }
    
    /**
     * Define o ID dos itens ativos.
     * @param string $activeItemId
     */
    public function setActiveItemId($activeItemId) 
    {
        $this->activeItemId = $activeItemId;
    }
    
    /**
     * Renders the menu.
     * @return string Código HTML do menu.
     */
    public function render() 
    {
        if (count($this->items)==0)
            return ''; // Não faça nada se não houver itens.
        
        $result = '<nav class="navbar navbar-default" role="navigation">';
        $result .= '<div class="navbar-header">';
        $result .= '<button type="button" class="navbar-toggle" data-toggle="collapse"';
        $result .= 'data-target=".navbar-ex1-collapse">';
        $result .= '<span class="sr-only">Toggle navigation</span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '</button>';
        $result .= '</div>';
        
        $result .= '<div class="collapse navbar-collapse navbar-ex1-collapse">';        
        $result .= '<ul class="nav navbar-nav">';
        
        // Renderiza itens
        foreach ($this->items as $item) {
            if(!isset($item['float']) || $item['float']=='left')
                $result .= $this->renderItem($item);
        }
        
        $result .= '</ul>';
        $result .= '<ul class="nav navbar-nav navbar-right">';
        
        // Renderiza itens
        foreach ($this->items as $item) {
            if(isset($item['float']) && $item['float']=='right')
                $result .= $this->renderItem($item);
        }
        
        $result .= '</ul>';
        $result .= '</div>';
        $result .= '</nav>';
        
        return $result;
        
    }
    
    /**
     * Processa um item.
     * @param array $item As informações do item de menu.
     * @return string Código HTML do item.
     */
    protected function renderItem($item) 
    {
        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id==$this->activeItemId);
        $label = isset($item['label']) ? $item['label'] : '';
             
        $result = ''; 
     
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        
        if (isset($item['dropdown'])) {
            
            $dropdownItems = $item['dropdown'];
            
            $result .= '<li class="dropdown ' . ($isActive?'active':'') . '">';
            $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            $result .= $escapeHtml($label) . ' <b class="caret"></b>';
            $result .= '</a>';
           
            $result .= '<ul class="dropdown-menu">';
            foreach ($dropdownItems as $item) {
                $link = isset($item['link']) ? $item['link'] : '#';
                $label = isset($item['label']) ? $item['label'] : '';
                
                $result .= '<li>';
                $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
                $result .= '</li>';
            }
            $result .= '</ul>';
            $result .= '</li>';
            
        } else {        
            $link = isset($item['link']) ? $item['link'] : '#';
            
            $result .= $isActive?'<li class="active">':'<li>';
            $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
            $result .= '</li>';
        }
    
        return $result;
    }
}
