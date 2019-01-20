<?php

declare(strict_types=1);

namespace Stater;

class Transition
{

    public function get()
    {
        $map = [];

        $map[$this->start()][$this->end()] = $this;

        return $map;
    }

    /**
     * Reset object
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
        }

        return $this;

    }

}