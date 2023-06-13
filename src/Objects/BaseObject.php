<?php

namespace Mustafadex\PhpFireblocks\Objects;

class BaseObject
{
    /**
     * Private internal struct attributes
     * @var array
     */
    private array $attributes = [];

    /**
     * Set a value
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get a value
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Check if a key is set
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * This function will take an instance of a PHP stdClass and attempt to cast it to
     * the type of the specified $className.
     *
     * For example, we may pass 'Acme\Model\Product' as the $className.
     *
     * @param object $instance  an instance of the stdClass to convert
     *
     * @return mixed a version of the incoming $instance casted as the specified className
     */
    public static function cast($instance, $className = null)
    {
        $className = $className ?? get_called_class();
        $object = unserialize(sprintf(
            'O:%d:"%s"%s',
            \strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
        $object->init();
        return $object;
    }

    protected function init() {}
}