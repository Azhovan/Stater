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



}