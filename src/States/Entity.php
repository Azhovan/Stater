<?php

namespace Stater\States;

use Stater\DomainObject;
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

        $this->entityObject = new DomainObject();

        $this->setType();
    }

    /**
     * Set the default type of entity object
     *
     * @return  void
     */
    public function setType(): void
    {
        $this->entityObject->type = $this->getType();
    }

    /**
     * Type of the Entity
     *
     * @return mixed
     */
    public function getType(): string
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
            $this->entityObject->name = $this->data["name"];

            if (isset($this->data["data"])) {
                $this->entityObject->data = $this->data["data"];
            }
        } else {
            $this->entityObject->name = $this->data;
        }

        return $this->entityObject;
    }
}