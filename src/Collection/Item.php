<?php

namespace Wicked\Dumper\Collection;

use Wicked\Dumper\Data;

class Item extends Data
{
    /*
     * The Item's index or key
     */
    private $key;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }
}
