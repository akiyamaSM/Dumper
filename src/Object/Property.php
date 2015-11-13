<?php

namespace Wicked\Dumper\Object;

class Property
{
    /**
     * @var \Wicked\Dumper\Data|Structure
     */
    public $data;

    /**
     * @var string
     */
    public $visibility;

    /**
     * @var bool
     */
    public $static;

    public function __construct($data = null, $visibility = 'public', $static = false)
    {
        $this->data = $data;
        $this->visibility = $visibility;
        $this->static = $static;
    }

    /**
     * @return \Wicked\Dumper\Data|Structure
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \Wicked\Dumper\Data|Structure $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

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
