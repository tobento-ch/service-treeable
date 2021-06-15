<?php

/**
 * TOBENTO
 *
 * @copyright    Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Treeable;

/**
 * Traverser
 */
class Traverser
{
    /**
     * @var null|callable
     */    
    protected $itemHandler = null;

    /**
     * @var null|callable
     */    
    protected $before = null;
    
    /**
     * @var null|callable
     */    
    protected $after = null;    
    
    /**
     * Create a new Traverser.
     *
     * @param array $items The items to traverse.
     * @param string $children The children key.
     */    
    public function __construct(
        protected array $items,
        protected string $children = 'children',
    ) {}

    /**
     * Set the item handler.
     *
     * @param callable $callback function(array|object $item, int $level): string { }
     * @return static $this
     */    
    public function item(callable $callback): static
    {
        $this->itemHandler = $callback;
        return $this;
    }

    /**
     * Set the before handler.
     *
     * @param callable $callback function(int $level): string { }
     * @return static $this
     */    
    public function before(callable $callback): static
    {
        $this->before = $callback;
        return $this;
    }
    
    /**
     * Set the after handler.
     *
     * @param callable $callback function(int $level): string { }
     * @return static $this
     */    
    public function after(callable $callback): static
    {
        $this->after = $callback;
        return $this;
    }    
    
    /**
     * Render the items.
     * 
     * @return string
     */
    public function render(): string
    {
        if (empty($this->items)) {
            return '';    
        }
        
        $traverse = function($items, int $level) use (&$traverse) {
    
            $html = '';
            
            if (is_callable($this->before))
            {
                $html .= call_user_func_array($this->before, [$level]);
            }
            
            foreach ($items as $item)
            {
                $childrenHtml = '';
                
                if ($item instanceof Treeable)
                {
                    if (!empty($item->getTreeChildren()))
                    {
                        $childrenHtml = $traverse($item->getTreeChildren(), $level+1);
                    }
                }
                else
                {
                    if (!empty($item[$this->children])) {
                        $childrenHtml = $traverse($item[$this->children], $level+1);
                    }
                }
                
                if (is_callable($this->itemHandler))
                {
                    $html .= call_user_func_array($this->itemHandler, [$item, $childrenHtml, $level]);
                }
            }
            
            if (is_callable($this->after))
            {
                $html .= call_user_func_array($this->after, [$level]);
            }
            
            return $html;
        };
        
        return $traverse($this->items, 0);
    }
        
    /**
     * Returns the string representation of the menu.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }    
}