<?php

/*
 * (c) Eric Gagnon <gagnonericj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wicked\Dumper\Object;

class Structure
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $final;

    /**
     * @var bool
     */
    private $abstract;

    /**
     * @var string
     */
    private $parent;

    /**
     * @var string|array
     */
    private $interfaces;

    /**
     * @var string|array
     */
    private $traits;

    /**
     * @var Property[]
     */
    public $properties;

    /**
     * @var Method|Method[]
     */
    public $methods;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
        if (!is_null($this->parent)) {
            return 'extends ' .$this->parent;
        }
        return null;
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
        if(!empty($this->interfaces)) {
           return 'implements '  . implode(', ', $this->interfaces);
        }
        return null;
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
        if (!empty($this->traits)) {
           return implode(', ', $this->traits);
        }
        return 'none';
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
     * @return int
     */
    public function hasProperties()
    {
        return count($this->properties) > 0;
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
     * @return int
     */
    public function hasMethods()
    {
        return count($this->methods) > 0;
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
