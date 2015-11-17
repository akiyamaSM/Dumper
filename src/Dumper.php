<?php

/*
 * (c) Eric Gagnon <gagnonericj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wicked\Dumper;

use Wicked\Dumper\Collection\Collection;
use Wicked\Dumper\Collection\Item;
use Wicked\Dumper\Object\Method;
use Wicked\Dumper\Object\Property;
use Wicked\Dumper\Object\Structure;

class Dumper
{
    private $dump;

    /**
     * Prints Properties, Methods, Data, Items.
     *
     * @param $data
     */
    private function buildType($data)
    {
        if ($data instanceof Property) {
            if ($data->getValue() instanceof Collection || $data->getValue() instanceof Structure) {
                $this->dump .= sprintf(
                    '<samp class="child">%s %s %s:',
                    $data->getVisibility(),
                    $data->getType(),
                    $data->getName()
                );
                $this->buildDump($data->getValue());
            } else {
                $this->dump .= sprintf(
                    '<samp class="child">%s %s %s: %s %s',
                    $data->getVisibility(),
                    $data->getType(),
                    $data->getName(),
                    $data->getLength() ? ' [length : '.$data->getLength().']' : '',
                    $data->getValue()
                );
            }
            $this->dump .= '</samp>';
        } elseif ($data instanceof Method) {
            $this->dump .= sprintf(
                '<samp class="child">%s %s (%s)</samp>',
                $data->getVisibility(),
                $data->getName(),
                $data->getParameters() ?: '&nbsp;'
            );
        } elseif ($data instanceof Item) {
            if ($data->getValue() instanceof Collection || $data->getValue() instanceof Structure) {
                $this->dump .= sprintf(
                    '<samp class="child">[%s] =>',
                    $data->getKey()
                );
                $this->buildDump($data->getValue());
            } else {
                $this->dump .= sprintf(
                    '<samp class="child">[%s] => %s%s %s',
                    $data->getKey(),
                    $data->getType(),
                    $data->getLength() ? ' [length : '.$data->getLength().']' : '',
                    $data->getValue()
                );
            }
            $this->dump .= '</samp>';
        } elseif ($data instanceof Data) {
            $this->buildData($data);
        }
    }

    /**
     * @param $data
     */
    public function dump($data)
    {
        $cloner = new Cloner();
        $clone = $cloner->cloneData($data);
        $dump = $this->buildDump($clone);
        echo $dump;
    }

    /**
     * Call the correct dump* method based on what
     * instance it is of.
     *
     * @param $data
     */
    public function buildDump($data)
    {
        if ($data instanceof Structure) {
            $this->buildStructure($data);
        } elseif ($data instanceof Collection) {
            $isParent = ($data->count()) ? 'parent' : '';
            $this->dump .= sprintf(
                '<samp class="%s">(Array : length %s) <span class="arrow"></span> [',
                $isParent,
                $data->count()
            );
            $this->iterateOverList($data->getItems());
            $this->dump .= ']</samp>';
        } elseif ($data instanceof Data) {
            $this->buildData($data);
        }

        return $this->dump;
    }

    /**
     * Dump a Structure (Object) to the screen.
     *
     * @param Structure $structure
     */
    private function buildStructure(Structure $structure)
    {
        $abstractOrFinal = ($structure->isAbstract()) ? 'abstract ' : ($structure->isFinal()) ? 'final ' : '';
        $this->dump .= sprintf(
            '<samp class="parent">%sclass %s %s %s<span class="arrow"></span> {',
            $abstractOrFinal,
            $structure->getNamespace() ?: $structure->getName(),
            $structure->getParent(),
            $structure->getInterfaces()
        );

        $this->dump .= sprintf('<samp class="child">id : %s</samp>', $structure->getId());

        $this->dump .= sprintf('<samp class="child">traits : %s </samp>', $structure->getTraits());

        if ($structure->hasProperties()) {
            $this->dump .= '<samp class="parent"> properties : <span class="arrow"></span> {';
            foreach ($structure->getProperties() as $property) {
                $this->buildType($property);
            }
            $this->dump .= '}</samp>';
        }

        if ($structure->hasMethods()) {
            $this->dump .= '<samp class="parent"> methods : <span class="arrow"></span> {';
            foreach ($structure->getMethods() as $method) {
                $this->buildType($method);
            }
            $this->dump .= '}</samp>';
        }

        $this->dump .= '}</samp>';
    }

    /**
     * Dump Data, a Property, or an Item to the screen.
     *
     * @param $data
     */
    private function buildData($data, $class = false)
    {
        $this->dump .= sprintf(
            '<samp class="%s"> %s%s %s</samp>',
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
            $this->buildType($value);
        }
    }
}
