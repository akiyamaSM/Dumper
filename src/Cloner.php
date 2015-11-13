<?php namespace Wicked\Dumper;

use ReflectionObject;
use Reflector;
use Wicked\Dumper\Object\Method;
use Wicked\Dumper\Object\MethodParameter;
use Wicked\Dumper\Object\Property;
use Wicked\Dumper\Object\Structure;

class Cloner
{


    /**
     * @param $data
     * @return Structure|Data
     */
    public function cloneData($data)
    {
        $clone = null;
        if (is_object($data)) {
            $clone = $this->buildStructure($data);
        } elseif (is_array($data)) {

        } else {
            $clone = new Data();
            $clone->setType(gettype($data));
            $clone->setValue($data);
            if (is_string($clone->getValue())) {
                $clone->setLength($data);
            }
        }
        return $clone;
    }

    private function buildStructure($object)
    {
        $classReflection = new ReflectionObject($object);
        $structure = new Structure();
        $structure->setNamespace($classReflection->getNamespaceName());
        $structure->setName($classReflection->getName());
        $structure->setFinal($classReflection->isFinal());
        if ($parent = $classReflection->getParentClass()) {
            $structure->setParent($parent->getName());
        }
        $structure->setInterfaces($classReflection->getInterfaceNames());
        $structure->setTraits($classReflection->getTraitNames());
        foreach ($classReflection->getProperties() as $propertyReflection) {
            $data = new Data();
            $visibility = $this->visiblity($propertyReflection);
            $propertyReflection->setAccessible(true);

            $data->setName($propertyReflection->getName());
            $value = $propertyReflection->getValue($object);
            $data->setValue($value);
            $data->setType(gettype($value));

            if (is_string($value)) {
                $data->setLength($value);
            }

            $property = new Property($data, $visibility, $propertyReflection->isStatic());

            $structure->addProperty($property);

            $propertyReflection->setAccessible(false);
        }
        foreach ($classReflection->getConstants() as $constantName => $constantValue) {
            $data = new Data();
            $data->setType('constant');
            $data->setName($constantName);
            $data->setValue($constantValue);
            $data->setConstant(true);
            $property = new Property($data);
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
     * @param Reflector $reflection
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
