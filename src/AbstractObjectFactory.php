<?php

declare(strict_types=1);

namespace Stater;

abstract class AbstractObjectFactory
{
    public function create(array $data)
    {
        return $this->getObject($data);
    }

    abstract protected function getObject(array $data);
}