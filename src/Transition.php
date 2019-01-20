<?php

declare(strict_types=1);

namespace Stater;

use Closure;

class Transition
{

    public function get()
    {
        // check if there is starting point and start or event
        if (null == $this->start()) {
            throw  new \RuntimeException("start state was not set");
        }

        if (null == $this->end()) {
            throw  new \RuntimeException("end state was not set");
        }

        if (null == $this->event()) {
            throw  new \RuntimeException("event was not set for this transition");
        }

        $map = [];

        $map[$this->start()][$this->end()] = $this;

        return $map;
    }


    /**
     * Return or set condition,
     * if condition was set before, it will returns, neither it will set
     *
     * @param  Closure $callable
     * @return mixed
     */
    public function condition(Closure $callable = null)
    {
        if ($callable) {
            $this->condition = $callable();
            return $this;
        }

        return $this->condition;
    }

    /**
     * Return or set condition,
     * if condition was set before, it will returns, neither it will set
     *
     * @param  Closure $callable
     * @return mixed
     */
    public function callback(Closure $callable = null)
    {
        if ($callable) {
            $this->callback = $callable();
            return $this;
        }

        return $this->callback;
    }

    /**
     * Reset object
     *
     * @return void
     */
    public function reset(): void
    {
        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties();

        foreach ($properties as $property) {
            $this->{$property->getName()} = null;
        }

    }

    /**
     * Dynamic call to methods
     *
     * @param  $name
     * @param  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // get
        if (count($arguments) == 0) {
            return $this->{$name};

            // set
        } else if (count($arguments) == 1) {
            $this->{$name} = $arguments[0];
            return $this;
        }

        throw new \InvalidArgumentException("more than one argument not supported");
    }

}