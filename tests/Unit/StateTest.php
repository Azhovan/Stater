<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Stater\AbstractObject;
use Stater\States\State;

class StateTest extends TestCase
{

    private $stateObject;

    public function setUp()
    {
        parent::setUp();

        $this->stateObject = new State();
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

    // the state class must inherit behavior from AbstractObject
    public function test_create_state()
    {
        $this->assertInstanceOf(AbstractObject::class, $this->stateObject);
    }

    // creating collection of state and test they are exist
    public function test_create_function_with_inputs()
    {
        $state = $this->stateObject->create(
            [
                'state1',
                'state2',
                'state3'
            ]
        );

        $this->assertSame(3, count($state));

    }

    public function test_create_function_with_existing_index()
    {
        /**
         * @var State $state
         */
        $state = $this->stateObject->create(
            [
                'state1',
                'state2',
                'state3'
            ]
        );

        $objects = $state->getObjects();
        $this->assertSame("state1", $objects["state1"]->name);
    }

    public function test_create_single_input_and_complex_data()
    {
        $stateObject = [
            "name" => "state_name",
            "data" => [
                "user" => "test_user",
                "credit" => "250"
            ]
        ];

        /**
         * @var State $state
         */
        $state = $this->stateObject->create([
            $stateObject
        ]);

        // return stdClass object
        // $state();
        /*
         * array(1) {
                ["state_name"]=>
                  object(stdClass)#53 (3) {
                 ["type"]=>
                 string(5) "state"
                 ["name"]=>
                string(10) "state_name"
                ["data"]=>
               array(2) {
                    ["user"]=>
                    string(9) "test_user"
                    ["credit"]=>
                    string(3) "250"
              }
           }
        }*/

        $this->assertSame($stateObject["name"], $state()["state_name"]->name);
    }

    public function test_create_state_with_array_input_and_complex_data()
    {
        $stateObject1 = [
            [
                "name" => "state_name1",
                "data" => [
                    "user" => "test_user1",
                    "credit" => "250"
                ]
            ]
        ];

        $stateObject2 = [
            "name" => "state_name2",
            "data" => [
                "user" => "test_user2",
                "credit" => "100"
            ]
        ];

        /**
         * @var State $state
         */
        $state = $this->stateObject->create(
            [
                $stateObject1,
                $stateObject2,
            ]
        );

        $this->assertSame($stateObject2["name"], $state()["state_name2"]->name);
    }


}