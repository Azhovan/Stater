<?php

declare(strict_types=1);

namespace Stater\Event;

use InvalidArgumentException;
use Stater\AbstractObject;

class Event extends AbstractObject
{

    private $events = [];

    /**
     * @param  array $events
     * @throws InvalidArgumentException
     * @return self
     */
    public function create(array $events): self
    {
        if (empty($events)) {
            throw new InvalidArgumentException(
                "input couldn't be empty array"
            );
        }

        $this->init($events);

        return $this;
    }


    /**
     * @param  array $events
     * @return void
     */
    protected function init(array $events): void
    {
        foreach ($events as $event) {
            $this->events[$event] = $this->stateObject($event);
        }
    }

    /**
     * Access to child property objects
     *
     * @return mixed
     */
    protected function getObjects()
    {
        return $this->events;
    }
}