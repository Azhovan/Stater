<?php

namespace Stater\State;

use Stater\EntityInterface;
use stdClass;

class Entity implements EntityInterface
{

    /**
     * @var array|string 
     */
    private $data;

    /**
     * @var stdClass  
     */
    private $entityObject;

    /**
     * Entity constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;

        $this->entityObject = new stdClass();

        $this->setType();
    }


    public function setType()
    {
        $this->entityObject->type = $this->getType();
    }

    /**
     * Type of the Entity
     *
     * @return mixed
     */
    public function getType()
    {
        return "state";
    }

    /**
     * Build State entity
     *
     * @return stdClass
     */
    public function build(): stdClass
    {
        if (is_array($this->data)) {
            $this->entityObject->name = key($this->data);
            $this->entityObject->data = array_values($this->data);
        } else {
            $this->entityObject->name = $this->data;
        }

        return $this->entityObject;
    }
}