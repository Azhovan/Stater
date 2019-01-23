<?php

namespace Stater\Traits;

trait InstanceFinder
{

    /**
     * @var string
     */
    private $object;

    /**
     * @param $object
     * @return $this
     */
    public function object($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function instanceOf(string $class)
    {
        return $this->object instanceof $class;
    }


}