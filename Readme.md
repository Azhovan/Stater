Stater, An Advanced | Fast PHP State Machine Manager
====================================================

Stater is an advanced State Machine, all written in PHP. It can holds states, standalone conditions, callbacks and events with complex data structure 


### Concepts and terminology
- A **state** is a description of the status of a system that is waiting to execute a transition.
- A **transition** is a ``set of actions`` to be executed when a condition is fulfilled or when an ``event`` is received.
- A **finite-state machine (FSM)** or finite-state automaton (FSA, plural: automata), finite automaton, or simply a state machine, is a mathematical model of computation. It is an abstract machine that can be in exactly one of a finite number of states at any given time.[wikipedia](https://en.wikipedia.org/wiki/Finite-state_machine)

Features
--------
- Managing States, Transitions
- Defining Events for Transitions
- Creating fluent callback, conditions per Transition and Event
- Defining and Retrieving properties for events, states,
- Event Listenable transitions (next release)

Getting started
---------------
### Installation (via composer)
```js
{
      "require": {
        "azhovan/stater": "^0.1"
    }
}
```

### Define States
An `state` in **Stater** can be just and string or even a complex data structure.

Defining a `state` in both is very easy step, you can use `state factory` class or directly by
using `State` class:
 
```php
$state = [
            "name" => "state_name1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250",
                "vip" => true,
                ...
                ...
            ]
        ];
        
``` 

or very simple just a name:
```php
$state = ["state_name1"]
```
Creating `state` is very easy step. There are two ways.to convert these data structures 
simply call `state factory` class :

```php
$states = (new \Stater\States\Factory())
            ->create([
                $state1,
                $state2,
                $state3,
                ...
            ]);
```
or you can use directly State class
```php
$st = new Stater\States\State();
$state = $st->create(
            [
                'state1', // the name of the state1 (Mandatory)
                'state2', // the name of the state2 (Mandatory)
                'state3'  // the name of the state3 (Mandatory)
            ]
        );
        
// OR 
$st = ["state_name1"];
$state = $st->create($st);
```
remember that, any state at least should has `name` as field (when you are just use string as the name,
it will be used automatically)

The returned object by `factory` class or `Stater\States\State` class is very useful since
you want to access to different states when creating mass states. 

```php
$st = new Stater\States\State();
$state = $st->create(
            [
                'state1', // the name of the state1 (Mandatory)
                'state2', // the name of the state2 (Mandatory)
                'state3'  // the name of the state3 (Mandatory)
            ]
        );
        
// FORM 1
$stateObject = $state("state1") ;   

// FORM 2
$stateObject = $state()["state1"] ;

$allStates = $state();

...
``` 
At the very higher level all states are inherited from `stdClass`.
```php

class State extends AbstractObject
{
  ...
}

abstract class AbstractObject extends AbstractDomainObject implements Accessor, Countable
{
  ...
}

abstract class AbstractDomainObject extends DomainObject
{
  ...
}

class DomainObject extends stdClass
{
  ...
}
```
So you have the idea!.


### Define Events
`events` are actions which are needed to start a transition. with simple language, suppose 
there is transition like `a --------> b`, conceptually some things should happen when this Transition happend
it called `Event`. it is like an action.

Event's functionality are totally like states, feel free to play with them.

```php
$event = [
            "name" => "event_name",
            "data" => [
                "user" => "jabar_asadi",
                "email" => "asadi.jabar@outlook.com",
                "credit" => "10000",
                "availability" => "1-march",
                "vip" => true
            ],
            ...
            ...
        ];
        
 $events = (new \Stater\Events\Factory())
     ->create([
         $event
      ]);
      
  // FORM 1
  $eventObject = $event("event_name") ;   
  
  // FORM 2
  $stateObject = $event()["event_name"] ;
  
  $allEvents = $event();
  
  ...
  ...
                    

```
### Initializing StateMachine
To defining state machine, you need to define `state(s)` and `evenet(s)` and use 
state machine functionality. Remember taht, creating any state machine needs 2 states at least

creating also machine can be possible in two ways
1. step by step with state Machine object
2. mass create by factory class

lets explore all

**Creating state machine step by step**

```php
$stateMachine = new StateMachine();

$stateMachine
        ->state($states("state_name1"))
        ->on(
            $events("event_name"),
            function ($input1, $input2,...) {
                // code here
            }
        )
        ->transitionTo(
            $states("state_name2"),
            function () {
                // code here
            }
        );

```

**state** function 
It will receive a state (you created it based on mentioned instructions), and set it as 
the starting point of transition.

**on** function
It will receive an event (as an action to doing the transition), and `callback` function.
the `callback` function is optional and it is responsible for **validate** the transition
it is an **GUARD** to protect the transition based on your definition.The callback function
is `Closure`, so you can pass any parameter to it or use any variables in it's scope.
when a transition happened, client need to pass needed parameters (if defined in its definition)
to state machine.

**transitionTo** function 
It will receive a state as end of transition plus `Closure` as callback after transition 
finished. it is very helpful if you want to log something, hite external endpoint,... after transition
completed.

There are many examples placed in `tests/Unit/StateMachineTest.php`, feel free to check those function.

**Creating state machine by factory class**
This option is useful when you need to create whole the state machine in one step (from config file or database)

```php

// Create simple state machine with single entry
$machine = Stater\Machine\Factory::create(
    [
        "state" => [
            "name" => "state_1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ],
        "event" => [
            "name" => "event_name_1",
            "data" => [
                "user" => "jabar_asadi_1",
                "email" => "asadi.jabar@outlook.com",
                "credit" => "10000",
                "availability" => "1-march",
                "vip" => true
            ]
        ],
        "condition" => function () {
        },
        "callback" => function () {
        },
        "transitionTo" => [
            "name" => "state_1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ]

    ]
);

```

### Validation
Validation is almost the propose of the whole codes!. after creating state machine with 
above instructions, supose there is `a --------> b` transition,to check that simply do like : 
```php
$stateMachine->can("a", "b"); // will return true or false
```
note that **a** is the name of starting state, and **b** is the end of transition.
If you add **validation** like mentioned above, just pass the requirements parameters to 
state machine as an **array**. for example :
```php
$t2 = $stateMachine->can("a", "b", [
            "condition" => [
                "param1" => false,
                "param2" => true
            ]
        ]);
```
An array with index **condition** was passed to `can` function.

###Access to State Machine
Every state machine is containing a `Transition` instance when you are working with it.
so access to transition properties like `condition closure` , `callback function`, `starting state`, `end state`
... is very useful at least in case of debugging.

```php

$transitionObject = $machine->getTransitionObject();

$transition = $stateMachine->getMap()

$t = ($transition["start_state_1"]["end_state_2"])->condition();
$run = $t(false, true);

$c = ($transition["start_state_1"]["end_state_2"])->callback();
$run = $c(); // OR  $c($param1, $param2,...);

...
...

```

Run Tests
==========
run tests with `vendor/bin/phpunit tests/` command, at the root of the project.


Road Map
=========
- Add event listeners
- Add standalone Transition for State Machine class
- Add non-state-less support for objects

