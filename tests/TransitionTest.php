<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Stater\States\State;
use Stater\Transition;

class TransitionTest extends TestCase
{

    private $stateObject;

    public function setUp()
    {
        parent::setUp();

        $this->stateObject = new State();
    }


    public function test_functionality_with_chaining_methods()
    {
        $state = $this->stateObject->create(
            [
            'start',
            'state2',
            'end'
            ]
        );
        $objects = $state->getObjects();

        $transition = new Transition();

        $transition->start($objects["start"])
            ->end($objects["end"])
            ->event('custom_event')
            ->condition(
                function () {
                    return true;
                }
            )
            ->callback(
                function () {
                    return 5 + 2;
                }
            );

        $this->assertSame('start', ($transition->start())->name);

        $this->assertSame('custom_event', $transition->event());

        $this->assertSame('end', ($transition->end())->name);

        $this->assertSame(true, $transition->condition()());

        $this->assertSame(7, $transition->callback()());

    }


    public function test_get_the_transition_object_if_end_is_not_set()
    {
        $this->expectException(\RuntimeException::class);

        $state = $this->stateObject->create(
            [
            'start',
            'state2',
            'end'
            ]
        );
        $objects = $state->getObjects();

        $transition = new Transition();

        $transition->start($objects["start"])
            ->event('custom_event');

        $transition = new Transition();
        $transition->get();
    }


    public function test_get_the_transition_object_if_start_is_not_set()
    {
        $this->expectException(\RuntimeException::class);

        $state = $this->stateObject->create(
            [
            'start',
            'state2',
            'end'
            ]
        );
        $objects = $state->getObjects();

        $transition = new Transition();

        $transition->end($objects["end"])
            ->event('custom_event');

        $transition = new Transition();
        $transition->get();
    }


    public function test_get_the_transition_object_if_event_is_not_set()
    {
        $this->expectException(\RuntimeException::class);

        $state = $this->stateObject->create(
            [
            'start',
            'state2',
            'end'
            ]
        );
        $objects = $state->getObjects();

        $transition = new Transition();

        $transition->start($objects["start"])
            ->end($objects["end"]);

        $transition = new Transition();
        $transition->get();
    }

    public function test_get_transition_matrix()
    {
        $state = $this->stateObject->create(
            [
            'a',
            'b',
            'c'
            ]
        );
        $objects = $state->getObjects();

        $transition = new Transition();

        $transition->start($objects["a"])
            ->end($objects["b"])
            ->event('custom_event')
            ->condition(
                function () {
                    return true;
                }
            )
            ->callback(
                function () {
                    return 5 + 2;
                }
            );

        $hash = $transition->get();
        $this->assertInstanceOf(Transition::class, $hash['a']['b']);
    }

}