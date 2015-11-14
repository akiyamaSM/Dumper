<?php

namespace Wicked\Dumper\Object;

use Wicked\Dumper\Data;

class Property extends Data
{
    /**
     * @var string
     */
    private $visibility;

    /**
     * @var bool
     */
    private $static;


    /**
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->static;
    }

    /**
     * @param boolean $static
     */
    public function setStatic($static)
    {
        $this->static = $static;
    }
}
