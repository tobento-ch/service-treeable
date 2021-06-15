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
 * Build tree from array items.
 */
class ArrayTree
{
    /**
     * @var array<int, callable>
     */    
    protected array $each = [];        
        
    /**
     * Create a new ArrayTree
     *
     * @param array $items The items.
     * @param string $id The id name.
     * @param string $parent The parent name.
     * @param string $level The level name.
     * @param string $children The children name.
     * @param string $parentItem The parent item name.     
     */    
    public function __construct(
        protected array $items,
        protected string $id,
        protected string $parent,
        protected string $level = 'level',
        protected string $children = 'children',
        protected string $parentItem = 'parentItem'
    ) {}
    
    /**
     * Sorts the items.
     *
     * @param callable|string $callback
     * @return ArrayTree
     */    
    public function sort(callable|string $callback): ArrayTree
    {
        if (is_string($callback)) {    
            $callback = fn ($a, $b) => ($a[$callback] ?? null) <=> ($b[$callback] ?? null);
        }

        uasort($this->items, $callback);
        return $this;
    }

    /**
     * Filter the items.
     * 
     * @param callable $callable
     * @return ArrayTree
     */
    public function filter(callable $callable): ArrayTree
    {
        $this->items = array_filter($this->items, $callable);
        return $this;
    }

    /**
     * Filter the parents items.
     * 
     * @param string|int $id
     * @param callable $callable
     * @return ArrayTree
     */
    public function parents(null|string|int $id, callable $callable): ArrayTree
    {        
        foreach($this->items as $index => $item) {
            if ($id === ($item[$this->id] ?? null)) {
                
                $item = call_user_func_array($callable, [$item]);
                
                if (is_null($item)) {
                    unset($this->items[$index]);
                } else {
                    $this->items[$index] = $item;
                }
                
                $parent = $item[$this->parent] ?? null;
                
                if (is_string($parent) || is_int($parent)) {
                    $this->parents($parent, $callable);
                }
            }    
        }
        
        return $this;
    }
    
    /**
     * Iterate over each items.
     * 
     * @param callable $callable function(array $item, int $level): ?array {}
     * @return ArrayTree
     */
    public function each(callable $callable): ArrayTree
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
     * @param null|array $parentItem The parent item
     * @param int $level The level depth.
     * @return array The build tree
     */
    protected function buildTree(array $items, ?array $parentItem = null, int $level = 0): array
    {
        $tree = [];
        
        foreach($items as $item) {

            if (!is_array($item) || !array_key_exists($this->id, $item)) {
                continue;
            }

            $item[$this->parent] ??= null;
                    
            if ($item[$this->parent] === ($parentItem[$this->id] ?? null)) {
                
                $item[$this->level] = $level;
                $item[$this->parentItem] = $parentItem;
                
                foreach($this->each as $callable) {
                    if (is_null($item = call_user_func_array($callable, [$item, $level]))) {
                        continue 2;
                    }
                }
                
                $children = $this->buildTree($items, $item, $level+1);
                
                if ($children) {
                    $item[$this->children] = $children;
                }
                
                $tree[$item[$this->id]] = $item;
            }
        }
        
        return $tree;        
    }    
}