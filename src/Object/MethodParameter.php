<?php

/*
 * (c) Eric Gagnon <gagnonericj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wicked\Dumper\Object;

class MethodParameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * @var bool
     */
    private $hasDefaultValue = false;

    /**
     * @var string
     */
    private $typeHintedClass;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $declaringClass;

    /**
     * @var string
     */
    private $declaringFunction;

    /**
     * @var bool
     */
    private $expectingArray;

    /**
     * @var bool
     */
    private $expectingCallable;

    /*
     * @var bool
     */
    private $expectingReference;

    /**
     * @var
     */
    private $optional;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->hasDefaultValue = true;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return mixed
     */
    public function getTypeHintedClass()
    {
        return $this->typeHintedClass;
    }

    /**
     * @param mixed $typeHintedClass
     */
    public function setTypeHintedClass($typeHintedClass)
    {
        $this->typeHintedClass = $typeHintedClass;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getDeclaringClass()
    {
        return $this->declaringClass;
    }

    /**
     * @param mixed $declaringClass
     */
    public function setDeclaringClass($declaringClass)
    {
        $this->declaringClass = $declaringClass;
    }

    /**
     * @return mixed
     */
    public function getDeclaringFunction()
    {
        return $this->declaringFunction;
    }

    /**
     * @param mixed $declaringFunction
     */
    public function setDeclaringFunction($declaringFunction)
    {
        $this->declaringFunction = $declaringFunction;
    }

    /**
     * @return mixed
     */
    public function getExpectingArray()
    {
        return $this->expectingArray;
    }

    /**
     * @param mixed $expectingArray
     */
    public function setExpectingArray($expectingArray)
    {
        $this->expectingArray = $expectingArray;
    }

    /**
     * @return mixed
     */
    public function getExpectingCallable()
    {
        return $this->expectingCallable;
    }

    /**
     * @param mixed $expectingCallable
     */
    public function setExpectingCallable($expectingCallable)
    {
        $this->expectingCallable = $expectingCallable;
    }

    /**
     * @return mixed
     */
    public function getOptional()
    {
        return $this->optional;
    }

    /**
     * @param mixed $optional
     */
    public function setOptional($optional)
    {
        $this->optional = $optional;
    }

    /**
     * @return mixed
     */
    public function getExpectingReference()
    {
        return $this->expectingReference;
    }

    /**
     * @param mixed $expectingReference
     */
    public function setExpectingReference($expectingReference)
    {
        $this->expectingReference = $expectingReference;
    }

    public function getInfo()
    {
        $info = '';
        if ($this->typeHintedClass) {
            $info .= $this->typeHintedClass.' ';
        } elseif ($this->expectingArray) {
            $info .= 'array ';
        } elseif ($this->expectingCallable) {
            $info .= 'callable ';
        } elseif ($this->expectingReference) {
            $info .= '&';
        }
        $info .= $this->name;
        $info .= ($this->hasDefaultValue) ? ' = ' . var_export($this->defaultValue, true) : '';

        return $info;
    }
}
