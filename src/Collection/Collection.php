<?php

namespace Wicked\Dumper\Collection;

use Countable;

class Collection implements Countable
{

    private $items;

    /**
     * Add item to Collection
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * Returns the number of items in the Collection
     */
    public function count()
    {
        return count($this->items);
    }
}
