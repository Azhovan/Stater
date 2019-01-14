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
     * Set object name and data
     *
     * @param  EntityInterface $template
     * @return stdClass
     */
    protected function stateObject(EntityInterface $template): stdClass
    {
        return $template->build();
    }

}