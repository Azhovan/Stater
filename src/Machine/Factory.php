<?php

declare(strict_types=1);

namespace Stater\Machine;

class Factory
{
    /**
     * Create state machine with inputs
     *
     * $machine::create([
     *   [
     *       "state" => ["name" =>"state1", "data" => []],
     *       "event" => ["name" =>"event1", "data" => []],
     *       "condition" => closure(),
     *       "callback" => closure(),
     *       "transitionTo" => ["name" =>"state2", "data" => []]
     *   ]
     *
     * ]);
     *
     * @param  array $entries
     * @return mixed
     */
    public static function create(?array $entries)
    {
        $machine = new StateMachine(null);

        if (null === $entries || empty($entries)) {
            return $machine;
        }

        foreach ($entries as $entry) {

            $states = (new \Stater\States\Factory())
                ->create(
                    [
                    $entry["state"],
                    $entry["transitionTo"]
                    ]
                );

            $event = (new \Stater\Events\Factory())
                ->create(
                    [
                    $entry["event"]
                    ]
                );

            $machine
                ->state(
                    $states($entry["state"]["name"])
                )
                ->on(
                    $event($entry["event"]["name"]),
                    $entry["condition"]
                )
                ->transitionTo(
                    $states($entry["transitionTo"]["name"]),
                    $entry["callback"]
                )
                ->get();
        }

        return $machine;
    }

}