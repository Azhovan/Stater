<?php

declare(strict_types=1);

namespace Stater\Event;

use Stater\AbstractObjectFactory;

/**
 * Class Factory
 *
 * @package Stater\Event
 */
class Factory extends AbstractObjectFactory
{
    protected function getObject($data)
    {
        return (new Event())->create($data);
    }
}
