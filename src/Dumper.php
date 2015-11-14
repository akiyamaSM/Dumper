<?php

namespace Wicked\Dumper;

use GuzzleHttp\Tests\Stream\Str;
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
    private function printPrimitive($data)
    {
        if ($data instanceof Property) {
            if ($data->getValue() instanceof Collection || $data->getValue() instanceof Structure) {
                echo sprintf(
                    '<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:5px;">%s %s %s:</div>',
                    $data->getVisibility(), $data->getType(), $data->getName()
                );
                $this->dumpContents($data->getValue());
            } else {
                $this->dumpData($data);
            }
        } elseif ($data instanceof Method) {
            echo sprintf(
                '<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:5px;">%s
                 <div style="float:right;">%s (%s)</div>
                </div>',
                $data->getVisibility(),
                $data->getName(),
                $data->getParameters() ?: '&nbsp;'
            );
        } elseif ($data instanceof Data) {
            $this->dumpData($data);
        } elseif ($data instanceof Item) {
            if ($data instanceof Collection) {
                echo sprintf(
                    '<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:5px;">[%s] =></div>',
                    $data->getKey()
                );
                $this->dumpContents($data);
            } else {
               $this->dumpData($data);
            }
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
        exit;
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
            echo sprintf('<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:5px;">(Array:length %s) [</div>', $data->count());
            $this->iterateOverList($data->getItems());
            echo sprintf('<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:5px;">]</div>');
        } elseif ($data instanceof Data) {
            $this->printPrimitive($data);
        }
    }

    /**
     * Dump a Structure (Object) to the screen.
     *
     * @param Structure $structure
     */
    private function dumpStructure(Structure $structure)
    {
        $abstractOrFinal = ($structure->isAbstract()) ? 'abstract' : ($structure->isFinal()) ? 'final' : '';
        echo '<div style="max-width:80%;margin-bottom:15px;">';
        echo sprintf(
            '<div style="color:#ffffff;background-color:#6C7A89;padding:4px 10px;margin-bottom:15px;">%s class %s</div>',
            $abstractOrFinal, $structure->getNamespace() ?: $structure->getName()
        );
        foreach ($structure as $methodOrProperty) {
            $this->iterateOverList($methodOrProperty);
        }
        echo '</div>';
    }

    /**
     * Dump Data, a Property, or an Item to the screen
     *
     * @param $data
     */
    private function dumpData($data)
    {
        echo sprintf(
            '<div style="background-color:#6C7A89;%s;padding:4px 10px;margin-bottom:5px;">%s%s %s</div>',
            $this->getScalarStyle($data->getType()), $data->getType(), $data->getLength() ? ' [length: '
            .$data->getLength
            ().']'
            : '',
            $data->getValue()
        );
    }

    private function iterateOverList($list)
    {
        if (is_array($list) && !empty($list)) {
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
