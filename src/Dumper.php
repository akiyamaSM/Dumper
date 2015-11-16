<?php

namespace Wicked\Dumper;

use Wicked\Dumper\Collection\Collection;
use Wicked\Dumper\Collection\Item;
use Wicked\Dumper\Object\Method;
use Wicked\Dumper\Object\Property;
use Wicked\Dumper\Object\Structure;

class Dumper
{
    /**
     * Array of styles to be used.
     *
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
     * @param $type
     *
     * @return mixed
     */
    private function getScalarStyle($type)
    {
        switch ($type) {
            case 'string':
                $style = 'string';
                break;
            case 'integer':
            case 'double':
                $style = 'number';
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
     * Prints Properties, Methods, Data, Items.
     *
     * @param $data
     */
    private function dumpType($data)
    {
        if ($data instanceof Property) {
            if ($data->getValue() instanceof Collection || $data->getValue() instanceof Structure) {
                echo sprintf(
                    '<pre class="child">%s %s %s:',
                    $data->getVisibility(),
                    $data->getType(),
                    $data->getName()
                );
                $this->dumpContents($data->getValue());
            } else {
                echo sprintf(
                    '<pre class="child">%s %s %s: %s%s %s',
                    $data->getVisibility(),
                    $data->getType(),
                    $data->getName(),
                    $data->getType(),
                    $data->getLength() ? ' [length : '.$data->getLength().']' : '',
                    $data->getValue()
                );
            }
            echo '</pre>';
        } elseif ($data instanceof Method) {
            echo sprintf(
                '<pre class="child">%s %s (%s)</pre>',
                $data->getVisibility(),
                $data->getName(),
                $data->getParameters() ?: '&nbsp;'
            );
        } elseif ($data instanceof Item) {
            if ($data->getValue() instanceof Collection || $data->getValue() instanceof Structure) {
                echo sprintf(
                    '<pre class="child">[%s] =>',
                    $data->getKey()
                );
                $this->dumpContents($data->getValue());
            } else {
                echo sprintf(
                    '<pre class="child">[%s] => %s%s %s',
                    $data->getKey(),
                    $data->getType(),
                    $data->getLength() ? ' [length : '.$data->getLength().']' : '',
                    $data->getValue()
                );
            }
            echo '</pre>';
        } elseif ($data instanceof Data) {
            $this->dumpData($data);
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
        $this->injectStyles();
    }

    /**
     * Call the correct dump* method based on what
     * instance it is of.
     *
     * @param $data
     */
    private function dumpContents($data)
    {
        if ($data instanceof Structure) {
            $this->dumpStructure($data);
        } elseif ($data instanceof Collection) {
            echo sprintf(
                '<pre class="parent">(Array : length %s) ▼ [',
                $data->count()
            );
            $this->iterateOverList($data->getItems());
            echo ']</pre>';
        } elseif ($data instanceof Data) {
            $this->dumpData($data);
        }
    }

    /**
     * Dump a Structure (Object) to the screen.
     *
     * @param Structure $structure
     */
    private function dumpStructure(Structure $structure)
    {
        $abstractOrFinal = ($structure->isAbstract()) ? 'abstract ' : ($structure->isFinal()) ? 'final ' : chr(8);
        echo sprintf(
            '<pre class="parent">%sclass %s ▼ {',
            $abstractOrFinal,
            $structure->getNamespace() ?: $structure->getName()
        );

        echo '<pre class="parent"> properties : ▼ {';
        foreach ($structure->getProperties() as $property) {
            $this->dumpType($property);
        }
        echo '}</pre>';

        echo '<pre class="parent"> methods : ▼ {';
        foreach ($structure->getMethods() as $method) {
            $this->dumpType($method);
        }

        echo '}</pre>';
        echo '}</pre>';
    }

    /**
     * Dump Data, a Property, or an Item to the screen.
     *
     * @param $data
     */
    private function dumpData($data, $class = false)
    {
        echo sprintf(
            '<span class="%s"> %s%s %s</span>',
            $class,
            $data->getType(),
            $data->getLength() ? ' [length : '.$data->getLength().']' : '',
            $data->getValue()
        );
    }

    /**
     * @param $list
     */
    private function iterateOverList($list)
    {
        $list = (array) $list;
        foreach ($list as $key => $value) {
            $this->dumpType($value);
        }
    }

    private function injectStyles()
    {
        echo '<style>
                .parent {
                    cursor: pointer;
                }
            </style>';
    }
}
