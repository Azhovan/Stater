<?php

namespace Stater;

use stdClass;

interface EntityInterface
{
    /**
     * @return stdClass
     */
    public function build(): stdClass;
}