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

    /**
     * The string to be dumped to the screen
     *
     * @var string
     */
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
                    $data->getLength() ? ' [length : ' . $data->getLength() . ']' : '',
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
                    '<samp class="child">[%s] => ',
                    $data->getKey()
                );
                $this->buildDump($data->getValue());
            } else {
                $this->dump .= sprintf(
                    '<samp class="child">[%s] => %s%s %s',
                    $data->getKey(),
                    $data->getType(),
                    $data->getLength() ? ' [length : ' . $data->getLength() . ']' : '',
                    $data->getValue()
                );
            }
            $this->dump .= '</samp>';
        } elseif ($data instanceof Data) {
            $this->buildData($data);
        }
    }

    /**
     * Dumps the given data to the browser
     *
     * @param $data
     */
    public function dump($data)
    {
        $cloner = new Cloner();
        $clone = $cloner->cloneData($data);
        $dump = $this->buildDump($clone);
        echo $dump;
        $this->injectJSAndStyles();
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
            $this->dump .= '<samp class="parent child"> properties : <span class="arrow"></span> {';
            foreach ($structure->getProperties() as $property) {
                $this->buildType($property);
            }
            $this->dump .= '}</samp>';
        }

        if ($structure->hasMethods()) {
            $this->dump .= '<samp class="parent child"> methods : <span class="arrow"></span> {';
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
            $data->getLength() ? ' [length : ' . $data->getLength() . ']' : '',
            $data->getValue()
        );
    }

    /**
     * Iterates over an array of Items building each by type
     *
     * @param $list
     */
    private function iterateOverList($list)
    {
        $list = (array)$list;
        foreach ($list as $key => $value) {
            $this->buildType($value);
        }
    }

    /**
     * Add the JavaScript and CSS to the document
     */
    private function injectJSAndStyles()
    {
        $line =
            "<script>
    dumper = window.dumper || (function(document){
    //Array of elements that have 'children'
    var parents = document.getElementsByClassName('parent');

    //On each parent
    iterateWithCallback(parents, function(parent) {
        //Add the right arrow
        switchArrows(parent.firstElementChild);
        //Hide its children
        iterateWithCallback(parent.children, toggleChildVisibility);
        //Ready the listener
        parent.addEventListener('click', function(event) {
            iterateWithCallback(this.children, toggleChildVisibility);
            switchArrows(this.firstElementChild);
            event.stopPropagation();
        }, false);
    });


    /**
     * Calls the given function passing in each object
     *
     * @param {object[]} objects
     * @param {function} callback
     */
    function iterateWithCallback(objects, callback) {
        Array.prototype.filter.call(objects, function(object){
            callback(object);
        });
    }

    /**
     * Toggles the visibility of the given child, if it is not an arrow
     *
     * @param {object} child
     */
    function toggleChildVisibility(child) {
        if(child.className !== 'arrow') {
            if (child.style.display === 'none') {
                child.style.display = 'block';
            } else {
                child.style.display = 'none';
            }
        }
    }

    /**
     * Switches the given arrow from down to right
     *
     * @param {object} arrow
     */
    function switchArrows(arrow) {
        if(arrow.innerHTML.indexOf('▼') > -1) {
            arrow.innerHTML = '▶';
        } else {
            arrow.innerHTML = '▼';
        }
    }

    }(document));
    </script>
    <style>
        .parent {
            cursor: pointer;
        }
        .arrow {
            color: #69D2E7;
        }
        .child {
            margin: 3px 0 3px 20px;
        }
    </style>";
        echo $line;
    }
}
