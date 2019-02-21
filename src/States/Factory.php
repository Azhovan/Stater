<?php

declare(strict_types=1);

namespace Stater\States;

use Stater\AbstractObjectFactory;

class Factory extends AbstractObjectFactory
{
    protected function getObject(?array $data)
    {
        return (new State())->create($data);
    }
}
