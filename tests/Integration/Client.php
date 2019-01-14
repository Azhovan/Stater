<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Stater\AbstractObject;
use Stater\Machin\StateMachine\Factory;

class ClientTest extends TestCase
{

    private $state;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->event = (new \Stater\Event\Factory)
            ->create(
                [
                "event1",
                "event2",
                "event3"
                ]
            );

        $this->state = (new \Stater\State\Factory)
            ->create(
                [
                "first",
                "second",
                "third"
                ]
            );

    }

    public function test_state_factory_return_true_instance()
    {
        $this->assertInstanceOf(AbstractObject::class, $this->state);
    }


    public function test_event_factory_return_true_instance()
    {
        $this->assertInstanceOf(AbstractObject::class, $this->event);
    }


    public function test_create_state_machine()
    {

        $machine = (new StateMachine\Factory)->create();

        $machine->state($this->state, $data)
            // define actions or event
            ->on($this->event->name)
            // transit to state2
            ->transitionTo($this->state->state2)
            // do some thing after creating state machine and before returning machine
            ->promise(
                function () {
                }
            );

        $machine->resolve("$value");

        // after above codes,
        // state machine was created

        //visualoize
        $machine->print();
    }
}