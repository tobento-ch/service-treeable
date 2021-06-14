# Treeable Service

With the Treeable Service you can create and manipulating trees easily.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
- [Documentation](#documentation)
	- [Array Tree](#array-tree)
    - [Tree](#tree)
    - [Traverser](#traverser)
- [Credits](#credits)
___

# Getting started

Add the latest version of the Treeable service project running this command.

```
composer require tobento/service-treeable
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Array Tree

Array tree creates the following data structure:

```php
use Tobento\Service\Treeable\ArrayTree;

$items = [
    [
        'name' => 'cars',
    ],
    [
        'name' => 'VW',
        'parent' => 'cars',
    ],        
];

$items = (new ArrayTree($items, 'name', 'parent'))->create();

Array
(
    [cars] => Array
        (
            [name] => cars
            [parent] => 
            [level] => 0
            [parentItem] => 
            [children] => Array
                (
                    [VW] => Array
                        (
                            [name] => VW
                            [parent] => cars
                            [level] => 1
                            [parentItem] => Array
                                (
                                    [name] => cars
                                    [parent] => 
                                    [level] => 0
                                    [parentItem] => 
                                )

                        )

                )

        )

)
```

Change the item keys for your array needs.

```php
use Tobento\Service\Treeable\ArrayTree;

$tree = new ArrayTree($items, 'id', 'parent_id', 'level', 'children', 'parentItem');
```

### Sorting the array items.

```php
use Tobento\Service\Treeable\ArrayTree;

// sort by array key.
$items = (new ArrayTree($items, 'name', 'parent'))->sort('name')->create();

// sort by callable.
$items = (new ArrayTree($items, 'name', 'parent'))
    ->sort(fn ($a, $b) => $a['name'] > $b['name'])
    ->create();
```

### Filter the array items.

```php
use Tobento\Service\Treeable\ArrayTree;

$items = (new ArrayTree($items, 'name', 'parent'))
    ->filter(fn($i) => $i['name'] === 'cars')
    ->create();
```

### Iterating over the array items.

On the each() method, the tree data structure has already been build, so you may use this information.

```php
use Tobento\Service\Treeable\ArrayTree;

$items = (new ArrayTree($items, 'name', 'parent'))
    ->each(function($item, $level) {

        if ($level >= 1) {
            return null;
        }
        
        $parentItem = $item['parentItem'];

        return $item;
    })
    ->create();
```

### Iterating over the parent array items from a specific tree item.

```php
use Tobento\Service\Treeable\ArrayTree;

$items = (new ArrayTree($items, 'name', 'parent'))
    ->parents('BMW', function($item) {
        $item['active'] = true;
        return $item;
    })
    ->create();
```

## Tree

Using the Tree class, items must implement the Treeable interface.

```php
use Tobento\Service\Treeable\Tree;
use Tobento\Service\Treeable\Treeable;
use Tobento\Service\Treeable\TreeableAware;

/**
 * Item
 */
class Item implements Treeable
{
    use TreeableAware;
            
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

    public function text(): string
    {
        return $this->text;
    }
}

$tree = new Tree([
    new Item('cars'),
    new Item('VW', 'cars'),
    new Item('BMW', 'cars'),
]);

$items = $tree->create();
```

### Sorting items

```php
use Tobento\Service\Treeable\Tree;

$tree = new Tree([
    new Item('cars'),
    new Item('VW', 'cars'),
    new Item('BMW', 'cars'),
]);

$tree->sort(fn ($a, $b) => $a->text() <=> $b->text());

$items = $tree->create();
```

### Filtering items

```php
use Tobento\Service\Treeable\Tree;

$tree = new Tree([
    new Item('cars'),
    new Item('VW', 'cars'),
    new Item('BMW', 'cars'),
]);

$tree->filter(fn($i) => $i->text() === 'cars');

$items = $tree->create();
```

### Iterating over each item

On the each() method, the tree data structure has already been build, so you may use this information.

```php
use Tobento\Service\Treeable\Tree;

$tree = new Tree([
    new Item('cars'),
    new Item('VW', 'cars'),
    new Item('BMW', 'cars'),
]);

$tree->each(function($item, $level) {
    if ($level >= 1) {
        return null;
    }
    
    //$item->getTreeLevel();
    
    return $item;
});

$items = $tree->create();
```

### Iterating over the parent items from a specific tree item.

```php
use Tobento\Service\Treeable\Tree;

$tree = new Tree([
    new Item('cars'),
    new Item('VW', 'cars'),
    new Item('BMW', 'cars'),
]);

$tree->parents('VW', function($item) {
    $item->active(true);
    return $item;
});

$items = $tree->create();
```

## Traverser

Traverse over array tree items.

```php
use Tobento\Service\Treeable\Traverser;
use Tobento\Service\Treeable\ArrayTree;

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

$tree = new ArrayTree($items, 'name', 'parent');

// change the children key if needed.
$html = (new Traverser($tree->create(), 'children'))
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
```

```html
<ul>
    <li>cars
        <ul>
            <li>VW</li>
            <li>BMW</li>
        </ul>
    </li>
</ul>
```

Traverse over treeable items.

```php
use Tobento\Service\Treeable\Traverser;
use Tobento\Service\Treeable\Tree;

$tree = new Tree([
    new Item('cars'),
    new Item('VW', 'cars'),
    new Item('BMW', 'cars'),
]);

// change the children key if needed.
$html = (new Traverser($tree->create()))
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
```

```html
<ul>
    <li>cars
        <ul>
            <li>VW</li>
            <li>BMW</li>
        </ul>
    </li>
</ul>
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)