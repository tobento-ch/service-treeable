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
 * Build tree from treeable items.
 */
class Tree
{
    /**
     * @var array
     */    
    protected array $each = [];
            
    /**
     * Create a new Tree from treeables.
     *
     * @param array $items The items.
     */    
    public function __construct(
        protected array $items
    ) {}

    /**
     * Sorts the items.
     *
     * @param callable $callback
     * @return Tree
     */    
    public function sort(callable $callback): Tree
    {
        uasort($this->items, $callback);
        return $this;
    }
    
    /**
     * Filter the items.
     * 
     * @param callable $callable
     * @return Tree
     */
    public function filter(callable $callable): Tree
    {
        $this->items = array_filter($this->items, $callable);
        return $this;
    }

    /**
     * Filter the parents items.
     * 
     * @param string|int $id
     * @param callable $callable
     * @return Tree
     */
    public function parents(string|int $id, callable $callable): Tree
    {        
        foreach($this->items as $index => $item) {            
            if (
                $item instanceof Treeable
                && $id === $item->getTreeId()
            ){                
                $item = call_user_func_array($callable, [$item]);
                
                if (is_null($item)) {
                    unset($this->items[$index]);
                } else {
                    $this->items[$index] = $item;
                }
                
                if (!is_null($item->getTreeParent())) {
                    $this->parents($item->getTreeParent(), $callable);    
                }
            }    
        }
        
        return $this;
    }
        
    /**
     * Iterate over each items.
     * 
     * @param callable $callable function(Treeable $treeable, int $level): ?Treeable {}
     * @return Tree
     */
    public function each(callable $callable): Tree
    {    
        $this->each[] = $callable;
        return $this;
    }    
    
    /**
     * Create the tree
     *
     * @return array The created tree.
     */
    public function create(): array
    {    
        return $this->buildTree($this->items);
    }

    /**
     * Build the tree.
     * 
     * @param array $items The items.
     * @param null|Treeable $parentItem The parent value
     * @param int $level The level depth.
     * @return array The build tree
     */
    protected function buildTree(array $items, ?Treeable $parentItem = null, int $level = 0): array
    {
        $tree = [];
        
        foreach($items as $item)
        {
            if (! $item instanceof Treeable) {
                continue;
            }
            
            $parent = is_null($parentItem) ? null : $parentItem->getTreeId();
            
            if ($item->getTreeParent() === $parent)
            {
                $item->setTreeLevel($level);
                
                if ($parentItem) {
                    $item->setTreeParentItem($parentItem);
                }

                foreach($this->each as $callable) {
                    if (is_null($item = call_user_func_array($callable, [$item, $level]))) {
                        continue 2;
                    }
                }    
                
                $children = $this->buildTree($items, $item, $level+1);
                
                if ($children)
                {
                    $item->setTreeChildren($children);
                }
                
                $tree[$item->getTreeId()] = $item;
            }
        }
        
        return $tree;        
    }    
}