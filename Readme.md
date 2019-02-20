
[![Build Status](https://travis-ci.org/Azhovan/Stater.svg?branch=master)](https://travis-ci.org/Azhovan/Stater)

Stater, An Advanced | Fast PHP State Machine Manager
====================================================

Stater is an advanced State Machine, all written in PHP. It can hold states, standalone conditions, callbacks and events with a complex data structure 


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
 composer require azhovan/stater

```

### Defining States

Defining a `state` is an easy step, you can use `state factory` class or directly by
using `State` class. A `state` in **Stater** can be just and simple primitive `string` or
even a complex `array`.

complex data format:
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

simple `state`: 
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
for example, the data block will hold extra information about the state 

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

The returned object by `factory` class or `Stater\States\State` class may be used to access to state objects. 

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
At the very higher level, all states are inherited from `stdClass`.
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


### Defining Events
Events are actions to starting a transition. Suppose a transition
like `a -----> b`, to change the state from `a` to `b` some actions should happen (like an event, or action by the user)

From the modeling perspective, `Events` are like `States` in **Stater**. Both of them are `DomainObject`. They are objects which created, initialized, refined, etc. What really makes them different is how to use these concepts inside the state machine.
So all the functionality for Events is exactly like the States.
 
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

There are a few steps to do:
- Create an event(s)
  - Every event **SHOULD** has unique name (it is not possible to have two different events with the same name)
  - Define **one event, per transition 
- Create State(s)
  - Every state **SHOULD** has unique name (it is not possible to have two different states with the same name)
  - define 2 states per transition (**start** of the transition, **end** of the transition)
- Attach **events** and **states** to State Machine

There are two ways to create state machine object
1. By `Stater\Machine\StateMachine` class
2. By `Stater\Machine\Factory` class

let's explore all

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

##### state(State $state) function 

Set the starting point of transition.

##### on(Event $event, Closure $condition) function

Set the event of transition with conditions.
The `closure` is optional. It is responsible for **validation**. you can pass any things inside of that, when 
you want to validate a transition by state machine object.


##### transitionTo(State $state, Closure $callback)  function 

Set the end of transition.
`$callback` is a callback function that will run after transition competition.

more examples :`tests/Unit/StateMachineTest` class.

##### get() function
Return the state machine map. It is an associated array contains `Transition` objects. 

```php

$map = $stateMachin->get(); 

// dump Transition object
var_dump($map["state1_name"]["state2_name"] ); 

```

#### Creating a state machine by the factory class 
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
Validation is like below:

```php
// is it possible to go from state "a" to state "b"
// there is no condition here, means there is **NOT** protection. just feasibility will checked
$stateMachine->can("a", "b"); // will return true or false
```

if there are conditions , simply pass an array to `can` function

```php
$t2 = $stateMachine->can("a", "b", [
            "condition" => [
                "param1" => false,
                "param2" => true
            ]
        ]);
```
index **condition** required here.

### Access to State Machine

State machine contains a `Transition` instance.
Access to transition properties like `condition` closure , `callback` closure, `starting state`, `end state`, etc looks useful.
 
#### Examples
For more examples, please refer to `tests` folder
 
 

Run Tests
==========
run tests with `composer tests` command, at the root of the project.


Road Map
=========
- Add event listeners
- Add standalone Transition for State Machine class
- Add non-state-less support for objects

