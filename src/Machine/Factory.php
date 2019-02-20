<?php

declare(strict_types=1);

namespace Stater\Machine;

use Stater\Events\Factory as EventFactory;
use Stater\States\Factory as StateFactory;

class Factory
{
    /**
     * Hold all states in the state machine
     *
     * @var array
     */
    private $states = [];

    /**
     * Holds all events in the state machine
     *
     * @var array
     */
    private $events = [];

    /**
     * @var StateMachine
     */
    private $machine;

    /**
     * Factory constructor.
     *
     * @param StateMachine $machine
     */
    public function __construct()
    {
        $this->machine = $machine ?? new StateMachine(null);
    }

    /**
     * Create state machine with inputs
     *
     * @param array $entries
     *
     * @return StateMachine
     */
    public function create(?array $entries): StateMachine
    {

        if (null === $entries || empty($entries)) {
            return $this->machine;
        }

        foreach ($entries as $entry) {

            $states = $this->states($entry["state"], $entry["transitionTo"]);
            $event = $this->events($entry["event"]);

            $this->make($entry, $states, $event);
        }

        return $this->machine;

    }

    /**
     * Make state machine object
     *
     * @param $entry
     * @param $states
     * @param $event
     */
    private function make($entry, $states, $event)
    {
        $states = $states->getObjects();

        $this->machine
            ->state(
                current($states)
            )
            ->on(
                $event,
                $entry["condition"]
            )
            ->transitionTo(
                next($states),
                $entry["callback"]
            );
    }

    /**
     * Create states
     *
     * @param $entry
     *
     * @return mixed
     */
    private function states(...$entry)
    {
        $states = [];

        foreach ($entry as $item) {
            if (is_string($item)) {
                if (array_key_exists($item, $this->states)) {
                    $states[] = $this->states[$item];
                    continue;

                } else {
                    $states[] = $item;
                    $this->states[$item] = $item;
                }
            }

            if (is_array($item)) {
                if (array_key_exists($item['name'], $this->states)) {
                    $states[] = $this->states[$item['name']];
                    continue;

                } else {
                    $states[] = $item;
                    $this->states[$item['name']] = $item;
                }
            }
        }

        return (new StateFactory())->create($states);
    }

    /**
     * Create events
     *
     * @param $entry
     *
     * @return mixed
     */
    private function events(...$entry)
    {
        $events = [];

        foreach ($entry as $item) {
            if (is_string($item)) {
                if (array_key_exists($item, $this->events)) {
                    $events[] = $this->events[$item];
                    continue;
                } else {
                    $events[] = $item;
                    $this->events[$item] = $item;
                }
            }

            if (is_array($item)) {
                if (array_key_exists($item['name'], $this->events)) {
                    $events[] = $this->events[$item['name']];
                    continue;
                } else {
                    $events[] = $item;
                    $this->events[$item['name']] = $item;
                }
            }
        }

        return (new EventFactory())->create($events);
    }

    /**
     * Return the state machine map
     *
     * @return array
     */
    public function get()
    {
        return $this->machine->get();
    }


}
