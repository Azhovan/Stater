<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Stater\Machine\StateMachine;
use Stater\Transition;

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
    public function test_attach_state_to_statemachine_with_complex_data()
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

        // attach the state to state machine
        $this->stateMachine
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


        // get state machine hash map
        $map = $this->stateMachine->get();

        //hash map contains of transition objects
        $this->assertInstanceOf(Transition::class, $map["state_name1"]["state_name2"]);
    }

    /**
     * start() function is used to create the starting point of transaction
     */
    public function test_state_function_will_set_start_point_of_transition()
    {
        $startState = [
            "name" => "state_name1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];

        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
            ]);

        // define staring point
        $machine = $this->stateMachine
            ->state($states()["state_name1"]);

        $transitionObject = $machine->getTransitionObject();

        $this->assertEquals($transitionObject->start(), $states()["state_name1"]);
    }

    /**
     * setting and getting event
     */
    public function test_set_event_on_transition()
    {
        $startState = [
            "name" => "state_name1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
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

        $events = (new \Stater\Events\Factory())
            ->create([
                $event
            ]);

        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
            ]);

        // define staring point
        $machine = $this->stateMachine
            ->state($states()["state_name1"])
            ->on($events()["event_name"]);

        $transitionObject = $machine->getTransitionObject();

        $this->assertEquals($transitionObject->event(), $events()["event_name"]);

    }

    /**
     * setting and getting event with condition
     * the condition can be used on validation classes
     */
    public function test_set_event_on_transition_with_dynamic_condition()
    {
        $startState = [
            "name" => "state_name1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
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

        $events = (new \Stater\Events\Factory())
            ->create([
                $event
            ]);

        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
            ]);

        // define staring point
        $machine = $this->stateMachine
            ->state($states()["state_name1"])
            ->on($events()["event_name"],
                function ($input) {
                    if ($input < 2)
                        return true;

                    return false;
                });

        $transitionObject = $machine->getTransitionObject();

        // $transitionObject->condition() will return condition which was set before
        $condition = $transitionObject->condition;
        $this->assertFalse($condition(21));
    }
}