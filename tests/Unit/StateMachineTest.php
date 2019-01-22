<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Stater\Machine\StateMachine;

class StateMachineTest extends TestCase
{

    private $stateObject;

    private $stateMachine;

    public function setUp()
    {
        parent::setUp();
        $this->stateMachine = new StateMachine(null);
    }

    /**
     * attach a state to state machine
     * return generated hash map
     */
    public function test_attach_state_to_state_machin()
    {
        $startState = [
            "name" => "state_name1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];
        $endState = [
            "name" => "state_name2",
            "data" => [
                "user" => "test_user2",
                "credit" => "400"
            ]
        ];
        $event = [
            "name" => "event_name",
            "data" => [
                "user" => "jabar_asadi",
                "email" => "asadi.jabar@outlook.com",
                "credit" => "10000",
                "availability" => "1-march",
                "vip" => true
            ]
        ];


        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
                $endState
            ]);

        $events = (new \Stater\Events\Factory())
            ->create([
                $event
            ]);

        /*var_dump($states);
        var_dump($events);*/

        // attach the state to state machine
        $a = $this->stateMachine
            ->state($states()["state_name1"])
            ->on(
                $events()["event_name"],
                function () {
                    return true;
                }
            )
            ->transitionTo(
                $states()["state_name2"],
                function () {
                    // code here
                }
            )->build();

        /*var_dump($a); die();*/

        // get state machin hash map
        $map = $this->stateMachine->get();
        var_dump($map); die();

        $this->assertInstanceOf(Transition::class, $map["state_name1"]["state_name2"]);


    }
}