<?php

/*
 * (c) Eric Gagnon <gagnonericj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wicked\Dumper\Collection;

use Countable;

class Collection implements Countable
{

    /**
     * An array of item objects
     *
     * @var Item[]
     */
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
