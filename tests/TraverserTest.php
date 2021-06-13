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
use Tobento\Service\Treeable\ArrayTree;
use Tobento\Service\Treeable\Traverser;
use Tobento\Service\Treeable\Test\Mock\Item;

/**
 * TraverserTest tests
 */
class TraverserTest extends TestCase
{
    protected $items;

    public function testArrayItemsWithoutChildren()
    {
        $items = [
            [
                'name' => 'VW',
            ],
            [
                'name' => 'BMW',
            ],
            [
                'name' => 'Fiat',
            ],            
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent'))->create();
        
        $html = (new Traverser($items))
            ->before(function($level) {
                return '<ul>';
            })
            ->item(function($item, $childrenHtml, $level) {

                if (!empty($childrenHtml)) {
                    return '<li>'.$item['name'].$childrenHtml.'</li>';
                }

                return '<li>'.$item['name'].'</li>';
            })
            ->after(function($level) {
                return '</ul>';
            })
            ->render();
        
        $this->assertEquals(
            '<ul><li>VW</li><li>BMW</li><li>Fiat</li></ul>',
            $html
        );       
    }
    
    public function testArrayItemsWithChildren()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'VW',
                'parent' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent'))->create();
        
        $html = (new Traverser($items))
            ->before(function($level) {
                return '<ul>';
            })
            ->item(function($item, $childrenHtml, $level) {

                if (!empty($childrenHtml)) {
                    return '<li>'.$item['name'].$childrenHtml.'</li>';
                }

                return '<li>'.$item['name'].'</li>';
            })
            ->after(function($level) {
                return '</ul>';
            })
            ->render();
        
        $this->assertEquals(
            '<ul><li>cars<ul><li>VW</li><li>BMW</li></ul></li></ul>',
            $html
        );       
    }
    
    public function testArrayItemsWithDifferentChildrenName()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'VW',
                'parent' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent', 'level', 'child'))->create();
        
        $html = (new Traverser($items, 'child'))
            ->before(function($level) {
                return '<ul>';
            })
            ->item(function($item, $childrenHtml, $level) {

                if (!empty($childrenHtml)) {
                    return '<li>'.$item['name'].$childrenHtml.'</li>';
                }

                return '<li>'.$item['name'].'</li>';
            })
            ->after(function($level) {
                return '</ul>';
            })
            ->render();
        
        $this->assertEquals(
            '<ul><li>cars<ul><li>VW</li><li>BMW</li></ul></li></ul>',
            $html
        );       
    }
    
    public function testArrayItemsWithInvalidChildrenName()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'VW',
                'parent' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent', 'level', 'child'))->create();
        
        $html = (new Traverser($items, 'childa'))
            ->before(function($level) {
                return '<ul>';
            })
            ->item(function($item, $childrenHtml, $level) {

                if (!empty($childrenHtml)) {
                    return '<li>'.$item['name'].$childrenHtml.'</li>';
                }

                return '<li>'.$item['name'].'</li>';
            })
            ->after(function($level) {
                return '</ul>';
            })
            ->render();
        
        $this->assertEquals(
            '<ul><li>cars</li></ul>',
            $html
        );       
    }    

    public function testArrayItemsEmpty()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'VW',
                'parent' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'invalid-name', 'parent'))->create();
        
        $html = (new Traverser($items))
            ->before(function($level) {
                return '<ul>';
            })
            ->item(function($item, $childrenHtml, $level) {

                if (!empty($childrenHtml)) {
                    return '<li>'.$item['name'].$childrenHtml.'</li>';
                }

                return '<li>'.$item['name'].'</li>';
            })
            ->after(function($level) {
                return '</ul>';
            })
            ->render();
        
        $this->assertEquals(
            '',
            $html
        );       
    }     
}