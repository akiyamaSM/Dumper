<?php

namespace Wicked\Dumper\Object;

class Structure
{
    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $final;

    /**
     * @var bool
     */
    public $abstract;

    /**
     * @var string
     */
    public $parent;

    /**
     * @var string|array
     */
    public $interfaces;

    /**
     * @var string|array
     */
    public $traits;

    /**
     * @var Property[]
     */
    public $properties;

    /**
     * @var Method|Method[]
     */
    public $methods;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isFinal()
    {
        return $this->final;
    }

    /**
     * @param boolean $final
     */
    public function setFinal($final)
    {
        $this->final = $final;
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param boolean $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return array|string
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @param array|string $interfaces
     */
    public function setInterfaces($interfaces)
    {
        $this->interfaces = $interfaces;
    }

    /**
     * @return array|string
     */
    public function getTraits()
    {
        return $this->traits;
    }

    /**
     * @param array|string $traits
     */
    public function setTraits($traits)
    {
        $this->traits = $traits;
    }

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    public function addProperty(Property $property)
    {
        $this->properties[] = $property;
    }

    /**
     * @param Property[] $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return Method|Method[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param Method|Method[] $methods
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    /**
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $this->methods[] = $method;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Check if Structure has a namespace
     */
    public function hasNamespace()
    {
        return !is_null($this->namespace);
    }
}
