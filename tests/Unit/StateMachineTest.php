<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Stater\Machine\StateMachine;
use Stater\Transition;

class StateMachineTest extends TestCase
{

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
            );


        // get state machine hash map
        $map = $this->stateMachine->get();

        // give start state details
        // print_r(($map["state_name1"]["state_name2"])->start());
//        .Stater\DomainObject Object
//        (
//           [type] => state
//           [name] => state_name1
//           [data] => Array
//             (
//              [user] => test_user1
//              [credit] => 250
//             )
//
//          )

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
        $condition = $transitionObject->condition();
        $this->assertFalse($condition(21));
    }


    public function test_set_end_state_on_transition()
    {
        $startState = [
            "name" => "start",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];;
        $endState = [
            "name" => "end",
            "data" => [
                "user" => "test_user2",
                "credit" => "400"
            ]
        ];

        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
                $endState
            ]);

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

        // define staring point
        $machine = $this->stateMachine
            ->state($states()["start"])
            ->on($events()["event_name"])
            ->transitionTo($states()["end"]);

        $transitionObject = $machine->getTransitionObject();

        $this->assertEquals($transitionObject->end(), $states()["end"]);
    }


    public function test_set_end_state_on_transition_will_callback()
    {
        $startState = [
            "name" => "start",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];;
        $endState = [
            "name" => "end",
            "data" => [
                "user" => "test_user2",
                "credit" => "400"
            ]
        ];

        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
                $endState
            ]);

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

        // define staring point
        $machine = $this->stateMachine
            ->state($states()["start"])
            ->on($events()["event_name"])
            ->transitionTo($states()["end"],
                function ($input) {
                    if ($input > 2)
                        return true;

                    return false;
                });

        $transitionObject = $machine->getTransitionObject();

        $callback = $transitionObject->callback();
        $this->assertTrue($callback(21));
    }

    public function test_add_transition_object_to_empty_state_machine_return_exception()
    {
        $this->expectException(\RuntimeException::class);

        $transition = new Transition();
        $this->stateMachine->add($transition);
    }

    /**
     *
     */
    public function test_generated_hash()
    {
        $startState = [
            "name" => "start",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];
        $endState = [
            "name" => "end",
            "data" => [
                "user" => "test_user2",
                "credit" => "400"
            ]
        ];

        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
                $endState
            ]);

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

        $machine = $this->stateMachine
            ->state($states()["start"])
            ->on($events()["event_name"])
            ->transitionTo($states()["end"],
                function ($input) {
                    if ($input > 2)
                        return true;

                    return false;
                });

        $this->assertIsArray($machine->get());

        // we have two states in the machine
        $this->assertSame(2, count($machine));
    }


    public function test_reset_function_will_renew_the_transition_object()
    {
        $startState = [
            "name" => "a",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];
        $endState = [
            "name" => "b",
            "data" => [
                "user" => "test_user2",
                "credit" => "400"
            ]
        ];

        $states = (new \Stater\States\Factory())
            ->create([
                $startState,
                $endState
            ]);

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

        // define b => a
        $this->stateMachine
            ->state($states()["b"])
            ->on($events()["event_name"])
            ->transitionTo($states()["a"],
                function ($input) {
                    return 4;
                })->get();

        // define  a => b
        $this->stateMachine
            ->state($states()["a"])
            ->on($events()["event_name"])
            ->transitionTo($states()["b"],
                function () {
                    return 8;
                })->get();


        // define b => b
        $this->stateMachine
            ->state($states()["b"])
            ->on($events()["event_name"],
                function ($input) {
                    return $input;
                })
            ->transitionTo($states()["b"],
                function ($input) {
                    return 3;
                })->get();

        // define a => a
        $this->stateMachine
            ->state($states()["a"])
            ->on($events()["event_name"])
            ->transitionTo($states()["a"],
                function () {
                    return 2;
                })->get();

        $map = $this->stateMachine->get();
        $this->assertEquals(6, count($this->stateMachine));

        // reset the transition object
        $this->stateMachine->reset();

        // add new states
        // define a => a
        $this->stateMachine
            ->state($states()["a"])
            ->on($events()["event_name"])
            ->transitionTo($states()["a"],
                function () {
                    return 2;
                })->get();

        $this->assertEquals(2, count($this->stateMachine));
    }


}