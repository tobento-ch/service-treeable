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
 * Trait TreeableAware
 */
trait TreeableAware
{
    /**
     * @var int the tree level depth.
     */    
    protected int $treeLevel = 0;

    /**
     * @var array The tree children [treeable, treeable]
     */    
    protected array $treeChildren = [];
    
    /**
     * @var null|Treeable The tree parent
     */    
    protected ?Treeable $treeParentItem = null;

    /**
     * Get the tree id
     *
     * @return string|int
     */
    public function getTreeId(): string|int
    {
        return 0;
    }
    
    /**
     * Get the tree parent
     *
     * @return null|string|int
     */
    public function getTreeParent(): null|string|int
    {
        return null;
    }

    /**
     * Set the tree parent item
     *
     * @param Treeable
     * @return void
     */
    public function setTreeParentItem(Treeable $item): void
    {
        $this->treeParentItem = $item;
    }
        
    /**
     * Get the tree parent item
     *
     * @return null|Treeable
     */
    public function getTreeParentItem(): ?Treeable
    {
        return $this->treeParentItem;
    }    
            
    /**
     * Sets the tree level depth.
     *
     * @param int
     * @return void
     */
    public function setTreeLevel(int $treeLevel): void
    {
        $this->treeLevel = $treeLevel;
    }

    /**
     * Gets the tree level depth.
     *
     * @return int
     */
    public function getTreeLevel(): int
    {
        return $this->treeLevel;
    }

    /**
     * Sets the tree children
     *
     * @param array
     * @return void
     */
    public function setTreeChildren(array $children): void
    {
        $this->treeChildren = $children;
    }

    /**
     * Gets the tree children
     *
     * @return array
     */
    public function getTreeChildren(): array
    {
        return $this->treeChildren;
    }        
}