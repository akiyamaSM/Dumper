<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php


ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once('vendor/autoload.php');

use Wicked\Test\NamespacedObject;

class ParentObject
{

}

interface InYourFace
{

}

trait SomeTrait
{
    private $traitProp = "trait";
}

class Object extends ParentObject implements InYourFace
{
    use SomeTrait;

    const CONSTANT = "blah";

    private $prop1;

    public $prop2;

    protected $prop3;

    public function __construct($prop1, $prop2, $prop3)
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

$object = new NamespacedObject("blue", 7, true);
$dumper = new \Wicked\Dumper\Dumper();

$dumper->killDump($object);
?>
</body>
</html>