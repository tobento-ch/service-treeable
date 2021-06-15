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
 * Treeable
 */
interface Treeable
{
    /**
     * Get the tree id
     *
     * @return string|int
     */
    public function getTreeId(): string|int;
    
    /**
     * Get the tree parent
     *
     * @return null|string|int
     */
    public function getTreeParent(): null|string|int;

    /**
     * Set the tree parent item
     *
     * @param Treeable $item
     * @return void
     */
    public function setTreeParentItem(Treeable $item): void;
        
    /**
     * Get the tree parent item
     *
     * @return null|Treeable
     */
    public function getTreeParentItem(): ?Treeable;
        
    /**
     * Sets the tree level depth.
     *
     * @param int $treeLevel
     * @return void
     */
    public function setTreeLevel(int $treeLevel): void;

    /**
     * Gets the tree level depth.
     *
     * @return int
     */
    public function getTreeLevel(): int;

    /**
     * Sets the tree children
     *
     * @param array $children
     * @return void
     */
    public function setTreeChildren(array $children): void;

    /**
     * Gets the tree children
     *
     * @return array
     */
    public function getTreeChildren(): array; 
}