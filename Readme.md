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

Defining a `state` is easy step, you can use `state factory` class or directly by
using `State` class. A `state` in **Stater** can be just and simple primitive `string` or
even a complex `aray`.

Define a state with complex data format:
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

Define simple `state`: 
```php
$state = ["state_name1"]
```

### Creating State Object

There are two ways.

1. simply call to `state factory` class :

```php
$states = (new \Stater\States\Factory())
            ->create([
                $state1,
                $state2,
                $state3,
                ...
            ]);
```

2. Use `State class`
```php
$st = new Stater\States\State();
$state = $st->create([
                'state1', // the name of the state1
                'state2', // the name of the state2 
                'state3'  // the name of the state3
            ]
        );
        
// OR 
$st = ["state_name1"];
$state = $st->create($st);
```
Every `state` has two fields:
- name (mandatory) 
- data (optional)

When you create simple states like `$st = ["state_name1"]` the `name` will be set automatically.
in case of complex states, you need to declare `data` field to keep those data tracked.
for example data block will hold the extra information about the state 

```php
$state = [
            "name" => "state_name1",
            "data" => [
                "info" => "25-54-444",
                "link" => "www.google.com",
                ...
                ...
            ]
        ];
```

The returned object by `factory` class or `Stater\States\State` class may be used to 
access to state objects. 

```php
$st = new Stater\States\State();
$states = $st->create(
            [
                'state1', // the name of the state1 (Mandatory)
                'state2', // the name of the state2 (Mandatory)
                'state3'  // the name of the state3 (Mandatory)
            ]
        );
        
// get the state object for state1
$stateObject = $states("state1") ;

//get name 
$stateObject->name;

//get data if exists
$stateObject->data

// get portion of states
// this will return an array, consisting of state1 and state2 object
$stateObject = $states("state1", "state2") ;  

// get the state object for state1
$stateObject = $states()["state1"] ;

// get all state objects
$allStates = $states();

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
Events are actions which are needed to start a transition. suppose there is transition
like `a -----> b`, to change the state from `a` to `b` some action should happened (like event, or action by user)

From modeling perspective, `Events` are like `States` in **Stater**. Both of them are `DomainObject`. They are objects 
which created, initialized, refined, etc.What really make them different is how to use these concepts inside the state machine.
So all the functionality for Events are exact like the State.
 
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
      
// get the event object for event1
$eventObject = $events("event1") ; 

//get name 
$eventObject->name;

//get data if exists
$eventObject->data

// get portion of events
// this will return an array, consisting of event1 and event2 object
$eventObject = $events("event1", "event2") ;  

// get the event object for event1
$eventObject = $events()["event1"] ;

// get all event2 objects
$allEvent = $events();
  
  ...
  ...
                    

```
### Initializing StateMachine
There are few steps:
- Create event(s)
  - there is no two event with same name
  - define one event per transition 
- Create State(s)
  - there is no two state with same name
  - define 2 states per transition (**start** of the transition, **end** of the transition)
- Attach Them to State Machine

Two way to create state machine object
1. By State Machine class
2. By factory class

lets explore all

#### Creating state machine step by step

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
        )->get();

```

##### state(...) function 

Set the starting point of transition.

##### on(...) function

Set the event of transition with conditions.
It will receive an event, and `callback` function.
the `callback` function is optional and it is responsible for **validation**.
it is an **GUARD** to protect the transition based on your definition.The callback function
is a `Closure`, so you can pass any parameter to it or use any variables in it's scope.
when a transition happened, caller routine needs to pass required parameters (if defined in its definition)
to state machine.

##### transitionTo(...)  function 

Set the end point of transition.
It will receive a state as end of transition plus `Closure` as callback after transition 
finished. use it to log, hit external endpoint,... after transition completed.

examples :`tests/Unit/StateMachineTest.php`.

##### get() function
Return the state machine mapp. this is associated array, which every cell contains `Transition` objects. 

```php

$map = $stateMachin->get(); 

// dump Transition object
var_dump($map["state1_name"]["state2_name"] ); 

```

#### Creating state machine by factory class 
This option is useful when you need to create whole the state machine in one step (load the data from config files, database, ...)

```php

// Create simple state machine with single entry
$machine = Stater\Machine\Factory::create([
        [
            "state" => ["name" => "state1", "data" => ""],
            "event" => ["name" => "event1", "data" => ""],
            "condition" => function () {
                return true;
            },
            "transitionTo" => ["name" => "state2", "data" => ""],
            "callback" => function () {
                // code here
            }
        ]
    ]
)->get();

```

#### Validation
Validation is very simple, just like below:

```php
// is it possible to go from state "a" to state "b"
// there is no condition here, means there is protection. just feasibility will checked
$stateMachine->can("a", "b"); // will return true or false
```

if there are condition **closure** at defining time with, simply pass an array to `can`

```php
$t2 = $stateMachine->can("a", "b", [
            "condition" => [
                "param1" => false,
                "param2" => true
            ]
        ]);
```
An array with index **condition** was passed to `can` function.

### Access to State Machine
Every state machine is containing a `Transition` instance when you are working with it.
so access to transition properties like `condition closure` , `callback function`, `starting state`, `end state`
... is very useful at least in case of debugging.
 

Run Tests
==========
run tests with `vendor/bin/phpunit tests/` command, at the root of the project.


Road Map
=========
- Add event listeners
- Add standalone Transition for State Machine class
- Add non-state-less support for objects

