<?php

declare(strict_types=1);

namespace Stater\States;

use Stater\AbstractObjectFactory;

class Factory extends AbstractObjectFactory
{
    protected function getObject($data)
    {
        return (new State())->create($data);
    }
}
