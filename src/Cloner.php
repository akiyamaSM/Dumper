<?php

/*
 * (c) Eric Gagnon <gagnonericj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wicked\Dumper;

use ReflectionObject;
use Reflector;
use Wicked\Dumper\Object\Method;
use Wicked\Dumper\Object\MethodParameter;
use Wicked\Dumper\Object\Property;
use Wicked\Dumper\Object\Structure;
use Wicked\Dumper\Collection\Collection;
use Wicked\Dumper\Collection\Item;

class Cloner
{
    /**
     * Clone the given data based on whether it is an object, array, or other (int, string, double, etc...).
     *
     * @param $data
     *
     * @return Structure|Collection|Data
     */
    public function cloneData($data)
    {
        $clone = null;
        if (is_object($data)) {
            $clone = $this->buildStructure($data);
        } elseif (is_array($data)) {
            $clone = $this->buildCollection($data);
        } else {
            $clone = new Data();
            $clone->setType(gettype($data));
            $clone->setValue($data);
            if (is_string($clone->getValue())) {
                $clone->setLength(strlen($data));
            }
        }

        return $clone;
    }

    /**
     * Build a new Structure (Object) from the given data.
     *
     * @param $object
     *
     * @return Structure
     */
    private function buildStructure($object)
    {
        $classReflection = new ReflectionObject($object);
        $structure = new Structure();
        $structure->setId(spl_object_hash($object));
        $structure->setNamespace($classReflection->getNamespaceName());
        $structure->setName($classReflection->getName());
        $structure->setFinal($classReflection->isFinal());
        if ($parent = $classReflection->getParentClass()) {
            $structure->setParent($parent->getName());
        }
        $structure->setInterfaces($classReflection->getInterfaceNames());
        $structure->setTraits($classReflection->getTraitNames());
        foreach ($classReflection->getProperties() as $propertyReflection) {
            $property = new Property();
            $visibility = $this->visiblity($propertyReflection);
            $propertyReflection->setAccessible(true);

            $property->setName($propertyReflection->getName());
            $value = $propertyReflection->getValue($object);
            if (is_object($value) || is_array($value)) {
                $property->setValue($this->cloneData($value));
            } else {
                $property->setValue($value);
            }
            $property->setType(gettype($value));

            if (is_string($value)) {
                $property->setLength(strlen($value));
            }
            $property->setVisibility($visibility);
            $property->setStatic($propertyReflection->isStatic());

            $structure->addProperty($property);

            $propertyReflection->setAccessible(false);
        }
        foreach ($classReflection->getConstants() as $constantName => $constantValue) {
            $property = new Property();
            $property->setType('constant');
            $property->setName($constantName);
            $property->setValue($constantValue);
            $property->setConstant(true);
            $structure->addProperty($property);
        }

        //docket method work
        foreach ($classReflection->getMethods() as $methodReflection) {
            $method = new Method();
            $method->setName($methodReflection->getName());
            $method->setFinal($methodReflection->isFinal());
            $method->setAbstract($methodReflection->isAbstract());
            $method->setStatic($methodReflection->isStatic());
            $method->setVisibility($this->visiblity($methodReflection));
            foreach ($methodReflection->getParameters() as $methodParameterReflection) {
                $methodParameter = new MethodParameter();
                $methodParameter->setName($methodParameterReflection->getName());
                if ($methodParameterReflection->isDefaultValueAvailable()) {
                    $methodParameter->setDefaultValue($methodParameterReflection->getDefaultValue());
                }
                $methodParameter->setTypeHintedClass($methodParameterReflection->getClass());
                $methodParameter->setPosition($methodParameterReflection->getPosition());
                $methodParameter->setDeclaringClass($methodParameterReflection->getDeclaringClass());
                $methodParameter->setDeclaringFunction($methodParameterReflection->getDeclaringFunction());
                $methodParameter->setExpectingArray($methodParameterReflection->isArray());
                $methodParameter->setExpectingCallable($methodParameterReflection->isCallable());
                $methodParameter->setOptional($methodParameterReflection->isOptional());
                $methodParameter->setExpectingReference($methodParameterReflection->isPassedByReference());
                $method->addParameter($methodParameter);
            }
            $structure->addMethod($method);
        }

        return $structure;
    }

    /**
     * Build a Collection (array) of Items from the given data.
     *
     * @param $data
     *
     * @return Collection
     */
    private function buildCollection($array)
    {
        $collection = new Collection();
        foreach ($array as $key => $value) {
            $item = new Item();
            if (is_array($value)) {
                $item->setValue($this->buildCollection($value));
            } elseif (is_object($value)) {
                $item->setValue($this->buildStructure($value));
            } else {
                $item->setValue($value);
                $item->setType(gettype($value));
                if ($item->getType() === 'string') {
                    $item->setLength(strlen($value));
                }
            }
            $item->setKey($key);
            $collection->addItem($item);
        }

        return $collection;
    }

    /**
     * Get the visibility from the Reflector.
     *
     * @param Reflector $reflection
     *
     * @return string
     */
    private function visiblity(Reflector $reflection)
    {
        $visibility = 'public';
        if ($reflection->isProtected()) {
            $visibility = 'protected';
        } else {
            if ($reflection->isPrivate()) {
                $visibility = 'private';
            }
        }

        return $visibility;
    }
}
