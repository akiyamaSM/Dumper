<?php

namespace Wicked\Dumper\Object;

class Method
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $visibility;

    /**
     * @var bool
     */
    public $final;

    /**
     * @var bool
     */
    public $static;

    /**
     * @var bool
     */
    public $abstract;

    /**
     * @var \Wicked\Dumper\Data|Structure
     */
    public $parameters;

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
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->final;
    }

    /**
     * @param bool $final
     */
    public function setFinal($final)
    {
        $this->final = $final;
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->static;
    }

    /**
     * @param bool $static
     */
    public function setStatic($static)
    {
        $this->static = $static;
    }

    /**
     * @return Wicked\Dumper\Data[]|Structure[]|array
     */
    public function getParameters()
    {
        if ($this->parameters) {
            return implode(', ', $this->parameters);
        }

        return '';
    }

    /**
     * @param \Wicked\Dumper\Data|Structure $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param MethodParameter $parameter
     */
    public function addParameter(MethodParameter $parameter)
    {
        $this->parameters[] = $parameter->getInfo();
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param bool $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }
}
