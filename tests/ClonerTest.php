<?php

use Wicked\Dumper\Cloner;

class ClonerTest extends PHPUnit_Framework_TestCase
{

    public function testBuildCollection()
    {
        $cloner = new Cloner();

        $array = [1, true , 'boom', 0.8];

        $clone = $cloner->cloneData($array);
        $this->assertInstanceOf('Wicked\Dumper\Collection\Collection', $clone);
        $this->assertEquals(4, $clone->count());
    }

    public function testBuildStructure()
    {
        $cloner = new Cloner();

        $object = new stdClass();

        $clone = $cloner->cloneData($object);
        $this->assertInstanceOf('Wicked\Dumper\Object\Structure', $clone);
        $this->assertEquals('stdClass', $clone->getName());
    }

    public function testBuildDataInteger()
    {
        $cloner = new Cloner();

        $data = 7;

        $clone = $cloner->cloneData($data);
        $this->assertInstanceOf('Wicked\Dumper\Data', $clone);
        $this->assertEquals('integer', $clone->getType());
        $this->assertEquals(null, $clone->getLength());
        $this->assertFalse($clone->isConstant());
    }

    public function testBuildDataDouble()
    {
        $cloner = new Cloner();

        $data = 0.11;

        $clone = $cloner->cloneData($data);
        $this->assertInstanceOf('Wicked\Dumper\Data', $clone);
        $this->assertEquals('double', $clone->getType());
        $this->assertEquals(null, $clone->getLength());
        $this->assertFalse($clone->isConstant());
    }

    public function testBuildDataString()
    {
        $cloner = new Cloner();

        $data = "Some string";

        $clone = $cloner->cloneData($data);
        $this->assertInstanceOf('Wicked\Dumper\Data', $clone);
        $this->assertEquals('string', $clone->getType());
        $this->assertEquals(strlen($data), $clone->getLength());
        $this->assertFalse($clone->isConstant());
    }

    public function testBuildDataBoolean()
    {
        $cloner = new Cloner();

        $data = false;

        $clone = $cloner->cloneData($data);
        $this->assertInstanceOf('Wicked\Dumper\Data', $clone);
        $this->assertEquals('boolean', $clone->getType());
        $this->assertEquals(null, $clone->getLength());
        $this->assertFalse($clone->isConstant());
    }
}
