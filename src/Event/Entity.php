<?php

namespace Stater\Event;

use Stater\EntityInterface;
use stdClass;

class Entity implements EntityInterface
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build State entity
     *
     * @return stdClass
     */
    public function build(): stdClass
    {
        $object = new stdClass();

        if (is_array($this->data)) {
            $object->name = key($this->data);
            $object->data = array_values($this->data);
        } else {
            $object->name = $this->data;
        }

        return $object;
    }

}