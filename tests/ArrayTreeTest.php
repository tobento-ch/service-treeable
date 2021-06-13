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

/**
 * ArrayTreeTest tests
 */
class ArrayTreeTest extends TestCase
{
    protected $items;

    public function testTreeReturnArrayStructure()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent'))->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'children' => [
                        'BMW' => [
                            'name' => 'BMW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );       
    }

    public function testTreeReturnArrayStructureWithAnotherName()
    {
        $items = [
            [
                'id' => 'cars',
            ],
            [
                'id' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'id', 'parent'))->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'id' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'children' => [
                        'BMW' => [
                            'id' => 'BMW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'id' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );       
    }

    public function testTreeReturnArrayStructureWithAnotherParent()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent_id' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent_id'))->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent_id' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'children' => [
                        'BMW' => [
                            'name' => 'BMW',
                            'parent_id' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent_id' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );         
    }
    
    public function testTreeReturnArrayStructureWithAnotherLevel()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent_id' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent_id', 'lev'))->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent_id' => null,
                    'lev' => 0,
                    'parentItem' => null,
                    'children' => [
                        'BMW' => [
                            'name' => 'BMW',
                            'parent_id' => 'cars',
                            'lev' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent_id' => null,
                                'lev' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );         
    }    
 
    public function testTreeReturnArrayStructureWithAnotherChildren()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent_id' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent_id', 'level', 'child'))->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent_id' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'child' => [
                        'BMW' => [
                            'name' => 'BMW',
                            'parent_id' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent_id' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );         
    }
 
    public function testTreeReturnArrayStructureWithAnotherParentItem()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent', 'level', 'children', 'pitem'))->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'pitem' => null,
                    'children' => [
                        'BMW' => [
                            'name' => 'BMW',
                            'parent' => 'cars',
                            'level' => 1,
                            'pitem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'pitem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );       
    }
    
    public function testTreeReturnArrayStructureWithInvalidName()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'foo', 'parent_id'))->create();
        
        $this->assertEquals(
            [],
            $items
        );         
    }

    public function testTreeReturnArrayStructureWithInvalidParent()
    {
        $items = [
            [
                'name' => 'cars',
            ],
            [
                'name' => 'BMW',
                'parent' => 'cars',
            ],        
        ];
        
        $items = (new ArrayTree($items, 'name', 'parent_id'))->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent_id' => null,
                    'level' => 0,
                    'parentItem' => null,
                ],
                'BMW' => [
                    'name' => 'BMW',
                    'parent' => 'cars',
                    'parent_id' => null,
                    'level' => 0,
                    'parentItem' => null,                         
                ],
            ],
            $items
        );         
    }

    public function testTreeSortByString()
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
        
        $items = (new ArrayTree($items, 'name', 'parent'))->sort('name')->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'children' => [
                        'VW' => [
                            'name' => 'VW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],                        
                        'BMW' => [
                            'name' => 'BMW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );       
    }

    public function testTreeSortByInvalidString()
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
        
        $items = (new ArrayTree($items, 'name', 'parent'))->sort('foo')->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'children' => [
                        'BMW' => [
                            'name' => 'BMW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],                        
                        'VW' => [
                            'name' => 'VW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );       
    }
    
    public function testTreeSortByCallable()
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
        
        $items = (new ArrayTree($items, 'name', 'parent'))
            ->sort(fn ($a, $b) => $a['name'] > $b['name'])
            ->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'children' => [
                        'VW' => [
                            'name' => 'VW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],                        
                        'BMW' => [
                            'name' => 'BMW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );       
    }
    
    public function testTreeFilter()
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
        
        $items = (new ArrayTree($items, 'name', 'parent'))
            ->filter(fn($i) => $i['name'] === 'cars')
            ->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                ]
            ],
            $items
        );       
    }
    
    public function testTreeEach()
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
        
        $items = (new ArrayTree($items, 'name', 'parent'))
            ->each(function($item, $level) {

                if ($level >= 1) {
                    return null;
                }

                return $item;
            })
            ->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                ]
            ],
            $items
        );       
    }
    
    public function testTreeParents()
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
        
        $items = (new ArrayTree($items, 'name', 'parent'))
            ->parents('VW', function($item) {
                $item['active'] = true;
                return $item;
            })
            ->create();
        
        $this->assertEquals(
            [
                'cars' => [
                    'name' => 'cars',
                    'active' => true,
                    'parent' => null,
                    'level' => 0,
                    'parentItem' => null,
                    'children' => [
                        'VW' => [
                            'name' => 'VW',
                            'parent' => 'cars',
                            'active' => true,
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'active' => true,
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],                        
                        'BMW' => [
                            'name' => 'BMW',
                            'parent' => 'cars',
                            'level' => 1,
                            'parentItem' => [
                                'name' => 'cars',
                                'active' => true,
                                'parent' => null,
                                'level' => 0,
                                'parentItem' => null,                               
                            ],                          
                        ],
                    ],
                ]
            ],
            $items
        );       
    }    
}