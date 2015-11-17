<?php

use Wicked\Dumper\Cloner;
use Wicked\Dumper\Dumper;

class DumperTest extends PHPUnit_Framework_TestCase
{
    public function testDumpSimpleVariable()
    {
        $data = 7;

        $output = $this->getDumpOutput($data);
        $this->assertXmlStringEqualsXmlString('<samp class=""> integer 7</samp>', $output);
    }

    public function testDumpObject()
    {
        $object = new stdClass();
        $output = $this->getDumpOutput($object);
        $this->assertXmlStringEqualsXmlString($output, '<samp class="parent">class stdClass  <span
        class="arrow"></span> {<samp class="child">id : ' . spl_object_hash($object) . '</samp><samp
        class="child">traits : none </samp>}</samp>');
    }

    private function getDumpOutput($data)
    {
        $cloner = new Cloner();
        $clone = $cloner->cloneData($data);
        $dumper = new Dumper();

        return $dumper->buildDump($clone);
    }
}
