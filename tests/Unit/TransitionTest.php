<?php

declare(strict_types=1);

namespace Tests\Unit;

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


    /**
     * Get and set starting state on transition
     */
    public function test_get_starting_state()
    {
        $state = $this->stateObject->create([
            'state1',
            'state2',
            'state3'
        ]);
        $objects = $state->getObjects();

        $transition = new Transition();
        $transition->start($objects["state1"]);
        $this->assertSame('state1', ($transition->start())->name);

    }



    /**
     * Get and set end state on transition
     */
    public function test_get_end_state()
    {
        $state = $this->stateObject->create([
            'state1',
            'state2',
            'state3'
        ]);
        $objects = $state->getObjects();

        $transition = new Transition();
        $transition->start($objects["state3"]);
        $this->assertSame('state3', ($transition->start())->name);

    }
}