<?php

namespace Wicked\Dumper;

use GuzzleHttp\Tests\Stream\Str;
use Wicked\Dumper\Collection\Collection;
use Wicked\Dumper\Object\Method;
use Wicked\Dumper\Object\Property;
use Wicked\Dumper\Object\Structure;

class Dumper
{
    /**
     * @var array
     */
    protected $styles = [
        'default' => 'background-color:#18171B;color:#FF8400;line-height:1.2em;font:12pxMenlo,Monaco,Consolas,
                      monospace;word-wrap:break-word;white-space:pre-wrap;position:relative;z-index:100000;',
        'number' => 'font-weight:bold;color:#3498db;',
        'const' => 'font-weight:bold;',
        'array' => 'font-weight:bold;color:#e67e22;',
        'bool' => 'font-weight:bold;color:#9b59b6;',
        'string' => 'font-weight:bold;color:#2ecc71;',
        'reference' => 'color:#A0A0A0;',
        'public' => 'color:#FFFFFF;',
        'protected' => 'color:#FFFFFF;',
        'private' => 'color:#FFFFFF;',
        'meta' => 'color:#B729D9;',
        'key' => 'color:#56DB3A;',
        'index' => 'color:#1299DA;',
    ];

    /**
     * @param $array
     * @param $class
     *
     * @return array
     */
    private function removeClassPrefix($array, $class)
    {
        $applied = [];
        foreach ($array as $key => $value) {
            $applied[str_replace('', '', $key)] = $value;
        }

        return $applied;
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    private function getPrimitiveStyle($type)
    {
        switch ($type) {
            case 'string':
                $style = 'str';
                break;
            case 'integer':
            case 'double':
                $style = 'num';
                break;
            case 'boolean':
                $style = 'bool';
                break;
            case 'array':
                $style = 'array';
                break;
            default:
                $style = 'default';
                break;
        }

        return $this->styles[$style];
    }

    /**
     * @param $data
     * @param bool|false $indent
     */
    private function printPrimitive($data, $indent = false)
    {
        if ($data instanceof Property) {
            echo sprintf(
                '<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:5px;">%s</div>',
                $data->getData()->getName()
            );
        } elseif ($data instanceof Method) {
            echo sprintf(
                '<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:5px;">%s
                 <div style="float:right;">%s (%s)</div>
                </div>',
                $data->getVisibility(),
                $data->getName(),
                $data->getParameters()
            );
        } elseif ($data instanceof Data) {
            echo $data->getName();
        }
    }

    /**
     * @param $data
     */
    public function killDump($data)
    {
        $cloner = new Cloner();
        $clone = $cloner->cloneData($data);
        $this->dumpContents($clone);
    }

    /**
     * @param $data
     */
    private function dumpContents($data)
    {
        if ($data instanceof Structure) {
            $this->dumpStructure($data);
        } elseif ($data instanceof Collection) {
        } elseif ($data instanceof Data) {
        }
        exit;
    }

    private function dumpStructure(Structure $structure)
    {
        echo '<div style="width:50%;margin-bottom:15px;">';
        echo sprintf(
            '<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;">%s</div>',
            $structure->getNamespace() ?: $structure->getName()
        );
        foreach ($structure as $property) {
            $this->iterateOverList($property);
        }
        echo '</div>';
    }

    private function iterateOverList($list)
    {
        if (is_array($list)) {
            echo '<ul>';
            foreach ($list as $key => $value) {
                if (is_array($value)) {
                    $this->iterateOverList($value);
                } else {
                    $this->printPrimitive($value);
                }
            }
            echo '</ul>';
        } else {
            $this->printPrimitive($list);
        }
    }
}
