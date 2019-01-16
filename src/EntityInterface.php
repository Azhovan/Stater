<?php

namespace Stater;

use stdClass;

interface EntityInterface
{
    /**
     * @return stdClass
     */
    public function build(): stdClass;

    /**
     * Type of the Entity
     *
     * @return mixed
     */
    public function getType();
}