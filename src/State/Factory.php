<?php

declare(strict_types=1);

namespace Stater\State;

class Factory extends AbstractObjectFactory
{
    protected function getObject($data)
    {
        return (new State())->create($data);
    }
}
