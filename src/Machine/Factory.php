<?php

declare(strict_types=1);

namespace Stater\Machine;

use Stater\AbstractObjectFactory;

class Factory extends AbstractObjectFactory
{
    /**
     * $machine::create([
     *   [
     *       "state" => ["name" =>"state1", "model" => []]
     *       "event" => ["name" =>"event1", "model" => []],
     *       "condition" => closure(),
     *       "callback" => closure(),
     *       "transitionTo" => ["name" =>"state2", "model" => []]
     *   ]
     *
     * ]);
     *
     * @param  array $entries
     * @return mixed
     */
    public function create(?array $entries)
    {
        $machine = parent::create([]);


        if (null === $entries) {
            return $machine;
        }

        foreach ($entries as $entry) {

            $states = (new \Stater\States\Factory())
                ->create([
                    $entry["state"],
                    $entry["transitionTo"]
                ]);

            $event = (new \Stater\Events\Factory())
                ->create([
                    $entry["event"]
                ]);

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
                ->build();
        }

        return $machine->get();
    }


    protected function getObject(?array $data)
    {
        return new StateMachine(null);
    }
}