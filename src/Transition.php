<?php

declare(strict_types=1);

namespace Stater;

use Closure;
use InvalidArgumentException;
use RuntimeException;

class Transition
{

    /**
     * Hold all the hash map of state machine
     *
     * @var array
     */
    private $map = [];

    /**
     * @return Transition
     */
    public function make()
    {
        return new self();
    }

    /**
     * Get the hash, generated by transition
     *
     * @return array
     */
    public function get(): array
    {
        // check if there is starting point and start or event
        if (null == $this->start()) {
            throw  new RuntimeException("START state was not set");
        }

        if (null == $this->end()) {
            throw  new RuntimeException("END state was not set");
        }

        if (null == $this->event()) {
            throw  new RuntimeException("EVENT was not set for this transition");
        }

        $this->map[$this->start()->name][$this->end()->name] = clone $this;

        return $this->map;
    }

    /**
     * Return or set condition,
     * If condition was set before, it will returns, neither it will set
     *
     * @param  Closure $callable
     * @return mixed
     */
    public function condition(Closure $callable = null)
    {
        if ($callable) {
            $this->condition = $callable;
            return $this;
        }

        return $this->condition;
    }

    /**
     * Return or set callback,
     * If condition was set before, it will returns, neither it will set
     *
     * @param  Closure $callable
     * @return mixed
     */
    public function callback(Closure $callable = null)
    {
        if ($callable) {
            $this->callback = $callable;
            return $this;
        }

        return $this->callback;
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
        if (count($arguments) == 0) {
            return $this->{$name};

        } else if (count($arguments) == 1) {
            $this->{$name} = $arguments[0];
            return $this;
        }

        throw new InvalidArgumentException("more than one argument not supported");
    }

}