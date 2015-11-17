<?php

/*
 * (c) Eric Gagnon <gagnonericj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wicked\Dumper;

class Data
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $length;

    /**
     * @var bool
     */
    private $constant = false;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
         if (is_bool($this->value)) {
             return var_export($this->value, true);
         }
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        if (is_bool($value)) {
            $value = var_export($value, true);
        } elseif (ctype_space($value)) {
            if ($value == "\n") {
                $value = '\\n';
            } elseif ($value == "\r") {
                $value = '\\r';
            } elseif ($value == " ") {
                $value = "\" \"";
            } elseif ($value == "\t") {
                $value = "\\t";
            }
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param string $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return boolean
     */
    public function isConstant()
    {
        return $this->constant;
    }

    /**
     * @param boolean $constant
     */
    public function setConstant($constant)
    {
        $this->constant = $constant;
    }
}
