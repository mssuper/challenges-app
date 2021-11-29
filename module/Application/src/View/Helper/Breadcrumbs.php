<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Esta classe auxiliar de visualização exibe breadcrumbs.
 */
class Breadcrumbs extends AbstractHelper 
{
    /**
     * Matriz de itens.
     * @var array 
     */
    private $items = [];
    
    /**
     * Construtor.
     * @param array $items Matriz de itens (opcional).
     */
    public function __construct($items=[]) 
    {                
        $this->items = $items;
    }
    
    /**
     * Define os itens.
     * @param array $items Items.
     */
    public function setItems($items) 
    {
        $this->items = $items;
    }
    
    /**
     * Processa o breadcrumbs.
     * @return string código HTML do breadcrumbs.
     */
    public function render() 
    {
        if (count($this->items)==0)
            return ''; // Não fazer nada se não houver itens.
        
        // O código HTML resultante será armazenado nesta var
        $result = '<ol class="breadcrumb">';
        
        // Get item count
        $itemCount = count($this->items); 
        
        $itemNum = 1; // contador de item
        
        // Percorrer os itens
        foreach ($this->items as $label=>$link) {
            
            // Tornar o último item inativo
            $isActive = ($itemNum==$itemCount?true:false);
                        
            // Renderizar o item atual
            $result .= $this->renderItem($label, $link, $isActive);
                        
            // incrementar contador de item
            $itemNum++;
        }
        
        $result .= '</ol>';
        
        return $result;
        
    }
    
    /**
     * Renders an item.
     * @param string $label
     * @param string $link
     * @param boolean $isActive
     * @return string Código HTML do item.
     */
    protected function renderItem($label, $link, $isActive) 
    {
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        
        $result = $isActive?'<li class="active">':'<li>';
        
        if (!$isActive)
            $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
        else
            $result .= $escapeHtml($label);
                    
        $result .= '</li>';
    
        return $result;
    }
}
