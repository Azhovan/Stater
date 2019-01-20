<?php
$event = (new \Stater\Event\Factory)
    ->create(
        [
            "event1",
            "event2",
            "event3"
        ]
    );

$state = (new \Stater\State\Factory)
    ->create(
        [
            "first",
            "second",
            "third"
        ]
    );


$machine = (new StateMachine\Factory)->create();
$machine->init($state->init);

$machine->state($state->first)
    // define actions or event
    ->on($this->event->name)
    // transit to state2
    ->transitionTo($this->state->state2)
    // do some thing after creating state machine and before returning machine
    ->promise(
        function () {
        }
    )->get();

$machine->resolve("$value");

// after above codes,
// state machine was created

//visualoize
$machine->print();
