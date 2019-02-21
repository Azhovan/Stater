<?php

declare(strict_types=1);

namespace Stater;

use Countable;

/**
 * Class AbstractObject
 *
 * @package Stater
 */
abstract class AbstractObject extends AbstractDomainObject implements Accessor, Countable
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
     * Access to Object data by Index or as an arguments
     *
     * @return mixed
     */
    public function __invoke()
    {
        try {

            $args = func_get_args();

            // call like $state()["state1"]
            if (empty($args)) {
                return $this->getObjects();
            }

            // call like $state("state1"):
            elseif (1 == count($args)) {
                return ($this->getObjects())[$args[0]];
            }

            // call like $event("event1", "event2");
            else {
                $arr = [];
                foreach ($args as $arg) {
                    $arr[$arg] = ($this->getObjects())[$arg];
                }

                return $arr;
            }

        } catch (\InvalidArgumentException $exception) {
            throw $exception;
        }

    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->getObjects());
    }

    /**
     * Check whether the object exists or not
     *
     * @param $key
     *
     * @return bool
     */
    protected function exists($key)
    {
        if (is_array($key)) {
            if (isset($this->getObjects()[$key["name"]])) {
                return true;
            }

        } else if (isset($this->getObjects()[$key])) {
            return true;
        }

        return false;
    }


}