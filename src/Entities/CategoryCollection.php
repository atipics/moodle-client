<?php

namespace Atipics\MoodleClient\Entities;

use Atipics\MoodleClient\GenericCollection;

/**
 * Class CategoryCollection
 * @package Atipics\MoodleClient\Entities
 */
class CategoryCollection extends GenericCollection
{
    /**
     * CategoryCollection constructor.
     * @param Category[] ...$categories
     */
    public function __construct(Category ...$categories)
    {
        $this->items = $categories;
    }

    /**
     * Add category to collection
     * @param Category $item
     */
    public function add(Category $item)
    {
        $this->items[$item->id] = $item;
    }

    /**
     * Remove category from collection
     * @param Category|integer $category
     */
    public function remove($category)
    {
        $id = ($category instanceof Category) ? $category->id : $category;
        if (array_key_exists($id, $this->items)) {
            unset($this->items[$id]);
        }
    }
}
