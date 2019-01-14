<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Stater\AbstractObject;
use Stater\State\State;

class StateTest extends TestCase
{

    private $stateObject;

    public function setUp()
    {
        parent::setUp();

        $this->stateObject = new State();
    }

    // the state class must inherit behavior from AbstractObject
    public function test_create_state()
    {
        $this->assertInstanceOf(AbstractObject::class, $this->stateObject);
    }


    public function test_create_function_with_inputs()
    {
        $state = $this->stateObject->create([
            'state1',
            'state2',
            'state3'
        ]);

        $this->assertSame(3, count($state));

    }

    public function test_create_function_with_existing_index()
    {
        /** @var State $state */
        $state = $this->stateObject->create([
            'state1',
            'state2',
            'state3'
        ]);

        $objects = $state->getObjects();
        $this->assertSame("state1", $objects["state1"]->name);
    }

    // input should be array
    public function test_create_state_need_input_array()
    {
        $this->expectException(\TypeError::class);
        $this->stateObject->create('');
    }

    // input shoud not be empty
    public function test_create_state_input_cannot_be_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->stateObject->create([]);
    }


}