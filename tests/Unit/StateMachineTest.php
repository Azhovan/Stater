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
        $this->stateMachine->addTransition($transition);
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
                function ($iput) {
                    return 2;
                })->get();

        $this->assertEquals(2, count($this->stateMachine));
    }

    public function test_get_domain_objects_data()
    {
        $state1 = [
            "name" => "state_1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];
        $state2 = [
            "name" => "state_2",
            "data" => [
                "user" => "test_user2",
                "credit" => "400"
            ]
        ];

        $states = (new \Stater\States\Factory())
            ->create([
                $state1,
                $state2
            ]);

        $event1 = [
            "name" => "event_name_1",
            "data" => [
                "user" => "jabar_asadi_1",
                "email" => "asadi.jabar@outlook.com",
                "credit" => "10000",
                "availability" => "1-march",
                "vip" => true
            ]
        ];

        $event2 = [
            "name" => "event_name_2",
            "data" => [
                "user" => "jabar_asadi_2",
                "email" => "asadi.jabar@outlook.com",
                "credit" => "10000",
                "availability" => "1-march",
                "vip" => true
            ]
        ];

        $events = (new \Stater\Events\Factory())
            ->create([
                $event1,
                $event2,
            ]);


        $this->assertEquals($events()["event_name_1"], $events("event_name_1"));
        $this->assertEquals($events()["event_name_2"], $events("event_name_2"));

        $this->assertEquals($states()["state_1"], $states("state_1"));
        $this->assertEquals($states()["state_2"], $states("state_2"));


        $statesArray = ["state_1" => $states()["state_1"], "state_2" => $states()["state_2"]];
        $this->assertEquals($statesArray, $states("state_1", "state_2"));

        $eventsArray = ["event_name_1" => $events()["event_name_1"], "event_name_2" => $events()["event_name_2"]];
        $this->assertEquals($eventsArray, $events("event_name_1", "event_name_2"));

    }

    public function test_check_go_to_next_state()
    {
        $this->seed_state_machine_2();

        $t2 = $this->stateMachine->can("a", "b");
        $t3 = $this->stateMachine->can("b", "c");
        $t4 = $this->stateMachine->can("c", "d");
        $t5 = $this->stateMachine->can("d", "e");
        $t6 = $this->stateMachine->can("e", "e");
        $this->assertTrue($t2 and $t3 and $t4 and $t5 and $t6);


        $t1 = $this->stateMachine->can("a", "c");
        $t3 = $this->stateMachine->can("e", "c");
        $t4 = $this->stateMachine->can("c", "a");
        $t5 = $this->stateMachine->can("d", "e");
        $t6 = $this->stateMachine->can("e", "e");
        $this->assertFalse($t1 and $t3 and $t4 and $t5 and $t6);

        $t1 = $this->stateMachine->can("a", "c");
        $this->assertFalse($t1  and $t3 and $t4 and $t5 and $t6);


    }

    private function seed_state_machine_2()
    {
        $st1 = [
            "name" => "a",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ];
        $st2 = [
            "name" => "b",
            "data" => [
                "user" => "test_user2",
                "credit" => "400"
            ]
        ];
        $st3 = [
            "name" => "c",
            "data" => [
                "user" => "test_user3",
                "credit" => "400"
            ]
        ];
        $st4 = [
            "name" => "d",
            "data" => [
                "user" => "test_user4",
                "credit" => "400"
            ]
        ];
        $st5 = [
            "name" => "e",
            "data" => [
                "user" => "test_user4",
                "credit" => "400"
            ]
        ];

        $states = (new \Stater\States\Factory())
            ->create([
                $st1,
                $st2,
                $st3,
                $st4,
                $st5,
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

        // define a => a
        $this->stateMachine
            ->state($states("a"))
            ->on($events("event_name"),
                function () {
                    return true;
                })
            ->transitionTo($states("a"),
                function () {
                    return 1;
                })->get();

        // define a => b
        $this->stateMachine
            ->state($states("a"))
            ->on($events("event_name"))
            ->transitionTo($states("b"),
                function () {
                    return 4;
                })->get();
        // define b => c
        $this->stateMachine
            ->state($states("b"))
            ->on($events("event_name"))
            ->transitionTo($states("c"))->get();

        // define c => d
        $this->stateMachine
            ->state($states("c"))
            ->on($events("event_name"))
            ->transitionTo($states("d"),
                function () {
                    return 4;
                })->get();
        // define d => e
        $this->stateMachine
            ->state($states("d"))
            ->on($events("event_name"))
            ->transitionTo($states("e"),
                function () {
                    return 4;
                })->get();
        // define e => e
        $this->stateMachine
            ->state($states("e"))
            ->on($events("event_name"))
            ->transitionTo($states("e"),
                function () {
                    return 4;
                })->get();

        // define e => e
        $this->stateMachine
            ->state($states("e"))
            ->on($events("event_name"),
                function ($input1, $input2) {
                    return (!$input1 and $input2);
                })
            ->transitionTo($states("a"),
                function () {
                    return 4;
                })->get();

        // define e => e
        $this->stateMachine
            ->state($states("c"))
            ->on($events("event_name"),
                function ($input1) {
                    return (!$input1);
                })
            ->transitionTo($states("b"),
                function () {
                    return 4;
                })->get();
    }

    public function test_check_state_machine_to_stop_on_false_condition()
    {
        $this->seed_state_machine_2();

        // test with different data type :: integers
        $t1 = $this->stateMachine->can("a", "a");
        $this->assertTrue($t1);

        /*
         * Below code is the equal behaviour
         *
         * $transition = $this->stateMachine->getMap();
         * $t = ($transition["a"]["a"])->condition();
         * $run = $t(false, true);
         */
        // test 2
        // change the conditions use Booleans
        $t2 = $this->stateMachine->can("e", "a", [
            "condition" => [
                "param1" => false,
                "param2" => true
            ]
        ]);

        $this->assertTrue($t2);


        // test 3
        // one parameter
        $t3 = $this->stateMachine->can("c", "b", [
            "condition" => [
                "param1" => true,
            ]
        ]);
        $this->assertFalse($t3);

        // test 4
        // without any parameter
        // $this->stateMachine->reset();
        $t4 = $this->stateMachine->can("b", "c");
        $this->assertTrue($t4);
    }

    /**------------------------------------------------------------
     *
     * State Machine Seeders
     *
     *-------------------------------------------------------------*/


}