<?php

use Wicked\Dumper\Dumper;

class DumperTest extends PHPUnit_Framework_TestCase
{

    public function testDumpInteger()
    {
        $dumper = new Dumper();
        $data = 7;

        ob_start();
        $dumper->killDump($data);
        $output = ob_get_clean();

        $this->assertEquals('<div style="background-color:#6C7A89;font-weight:bold;color:#3498db;padding:4px 10px;
        margin-bottom:5px;">integer 7</div>', $output);
    }
}
