<?php

namespace Wicked\Test;

class NamespacedObject
{
    const CONSTANT = 'blah';

    private $prop1;

    public $prop2;

    protected $prop3;

    public function __construct($prop1, $prop2, $prop3 = false)
    {
        $this->prop1 = $prop1;
        $this->prop2 = $prop2;
        $this->prop3 = $prop3;
    }

    private function getProp1()
    {
        return $this->prop1;
    }

    final public function getProp2()
    {
        return $this->prop2;
    }

    protected function setProp3($prop)
    {
        $this->prop3 = $prop;
    }
}
