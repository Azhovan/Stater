<?php

namespace Stater\Traits;

trait DecorateData
{
    /**
     * Decorate data with given criteria
     *
     * @param array $original
     * @param array $properties
     * @return array
     */
    private function decorate(array $original, array $properties)
    {
        foreach ($properties as $property => $type) {
            $original[$property] = $original[$property] ?? $this->make($type);
        }

        return $original;
    }

    /**
     * Create data type with given type
     *
     * @param string $type
     * @return \Closure|array
     */
    private function make(string $type)
    {
        if (ucfirst($type) == "Closure") {
            return (function ($inputs) {
                return $inputs;
            });
        } else if (strtolower($type) == "array") {
            return [];
        }
    }

}