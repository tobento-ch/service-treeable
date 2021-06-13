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

namespace Tobento\Service\Treeable\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Treeable\Tree;
use Tobento\Service\Treeable\Traverser;
use Tobento\Service\Treeable\Test\Mock\Item;
use Tobento\Service\Treeable\Test\Mock\InvalidItem;

/**
 * Tree tests
 */
class TreeTest extends TestCase
{
    public function testTree()
    {
        $tree = new Tree([
            new Item('cars'),
            new Item('flowers'),            
        ]);
        
        $this->assertEquals(
            '<ul><li>cars</li><li>flowers</li></ul>',
            $this->traverse($tree)
        );        
    } 
    
    public function testTreeWithChildren()
    {
        $tree = new Tree([
            new Item('cars'),
            new Item('VW', 'cars'),
        ]);
        
        $this->assertEquals(
            '<ul><li>cars<ul><li>VW</li></ul></li></ul>',
            $this->traverse($tree)
        );        
    }

    public function testTreeWithNotTreeableItem()
    {
        $tree = new Tree([
            new InvalidItem('cars'),
            new InvalidItem('flowers'),            
        ]);
        
        $this->assertEquals(
            '',
            $this->traverse($tree)
        );        
    }
    
    public function testTreeSort()
    {
        $tree = new Tree([
            new Item('cars'),
            new Item('VW', 'cars'),
            new Item('BMW', 'cars'),
        ]);
        
        $tree->sort(fn ($a, $b) => $a->text() <=> $b->text());
        
        $this->assertEquals(
            '<ul><li>cars<ul><li>BMW</li><li>VW</li></ul></li></ul>',
            $this->traverse($tree)
        );        
    }
    
    public function testTreeFilter()
    {
        $tree = new Tree([
            new Item('cars'),
            new Item('VW', 'cars'),
            new Item('BMW', 'cars'),
        ]);
        
        $tree->filter(fn($i) => $i->text() === 'cars');
        
        $this->assertEquals(
            '<ul><li>cars</li></ul>',
            $this->traverse($tree)
        );        
    }

    public function testTreeParents()
    {
        $tree = new Tree([
            new Item('cars'),
            new Item('VW', 'cars'),
            new Item('BMW', 'cars'),
        ]);
        
        $tree->parents('VW', function($item) {
            $item->sku('parent');
            return $item;
        });
        
        $this->assertEquals(
            '<ul><li>cars - parent<ul><li>VW - parent</li><li>BMW - </li></ul></li></ul>',
            $this->traverse($tree, true)
        );        
    }
    
    public function testTreeEach()
    {
        $tree = new Tree([
            new Item('cars'),
            new Item('VW', 'cars'),
            new Item('BMW', 'cars'),
        ]);
        
        $tree->each(function($item, $level) {
            if ($level >= 1) {
                return null;
            }

            return $item;
        });
        
        $this->assertEquals(
            '<ul><li>cars</li></ul>',
            $this->traverse($tree)
        );        
    }

    protected function traverse(Tree $tree, bool $withSku = false): string
    {
        if ($withSku) {
            return (new Traverser($tree->create()))
                ->before(function($level) {
                    return '<ul>';
                })
                ->item(function($item, $childrenHtml, $level) {

                    if (!empty($childrenHtml)) {
                        return '<li>'.$item->text().' - '.$item->getSku().$childrenHtml.'</li>';
                    }

                    return '<li>'.$item->text().' - '.$item->getSku().'</li>';
                })            
                ->after(function($level) {
                    return '</ul>';
                })
                ->render();        
        }
        
        return (new Traverser($tree->create()))
            ->before(function($level) {
                return '<ul>';
            })
            ->item(function($item, $childrenHtml, $level) {

                if (!empty($childrenHtml)) {
                    return '<li>'.$item->text().$childrenHtml.'</li>';
                }

                return '<li>'.$item->text().'</li>';
            })            
            ->after(function($level) {
                return '</ul>';
            })
            ->render();
    }    
}