<?php

declare(strict_types=1);

namespace Stater;
use Countable;

/**
 * Class AbstractObject
 *
 * @package Stater
 */
abstract class AbstractObject extends AbstractBuilder implements Accessor, Countable
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
     * @inheritdoc
     */
    public function count()
    {
        return count($this->getObjects());
    }


}