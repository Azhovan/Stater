<?php

declare(strict_types=1);

namespace Stater;

use stdClass;

/**
 * Class AbstractObject
 *
 * @package Stater
 */
abstract class AbstractObject
{

    /**
     * @param  $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $item = $this->getObjects();

        if (array_key_exists($item[$name], $item)) {
            return $item[$name];
        }

        return null;
    }

    /**
     * Access to child property objects
     *
     * @return mixed
     */
    abstract protected function getObjects();

    /**
     * Call dynamic methods, return stateObject
     *
     * @param  $name
     * @param  $arguments
     * @return stdClass
     */
    public function __call($name, $arguments)
    {
        $object = new stdClass();

        if (is_array($arguments)) {
            $object->data = $arguments;
        }

        $object->name = $name;

        return $object;
    }

    /**
     * @param  string|array $name
     * @return stdClass
     */
    protected function stateObject($name): stdClass
    {
        if (is_array($name)) {

            $this->name(key($name));
            $this->data(array_values($name));
        }

        return $this->name($name);
    }

}