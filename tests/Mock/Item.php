<?php
//ä utf-8 file
/*
 * TOBENTO
 *
 * Copyright (c) 2017, Tobias Strub, TOBENTO
 * All rights reserved.
 *
 * No part of this code may be reproduced,
 * copied, modified or adapted, without the prior written consent
 * of TOBENTO.
 */
declare(strict_types=1);

namespace Tobento\Service\Treeable\Test\Mock;

use Tobento\Service\Treeable\Treeable;
use Tobento\Service\Treeable\TreeableAware;

/**
 * Item
 */
class Item implements Treeable
{
    use TreeableAware;

    /**
     * @var string
     */    
    protected string $sku = '';
            
    /**
     * Create a new Item
     *
     * @param string
     * @param null|string
     */
    public function __construct(
        protected string $text,
        protected ?string $parent = null,
    ) {}
    
    public function text(): string
    {
        return $this->text;
    }

    /**
     * Set sku
     *
     * @param string
     * @return static
     */
    public function sku(string $sku): static
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }     
    
    /**
     * Get the tree id
     *
     * @return string|int
     */
    public function getTreeId(): string|int
    {
        return $this->text;
    }
    
    /**
     * Get the tree parent
     *
     * @return null|string|int
     */
    public function getTreeParent(): null|string|int
    {
        return $this->parent;
    }            
}