<?php

declare(strict_types=1);

namespace Stater\Events;

use InvalidArgumentException;
use Stater\AbstractObject;

class Event extends AbstractObject
{

    private $events = [];

    /**
     * @param  array $events
     * @throws InvalidArgumentException
     * @return AbstractObject
     */
    public function create(array $events): AbstractObject
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

            if ($this->exists($event)) {
                continue;
            }

            if (is_array($event)) {
                $this->events[$event["name"]] = $this->stateObject(new Entity($event));

            } else {
                $this->events[$event] = $this->stateObject(new Entity($event));
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function getObjects()
    {
        return $this->events;
    }

}