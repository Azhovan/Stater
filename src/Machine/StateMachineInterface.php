<?php

namespace Stater\Machine;

use Closure;
use Countable;
use IteratorAggregate;
use Serializable;
use Stater\DomainObject;

interface StateMachineInterface extends IteratorAggregate, Countable, Serializable
{

    /**
     * Create State and attached it to State Machine
     *
     * @param DomainObject $start
     * @return StateMachineInterface
     */
    public function state(DomainObject $start): self;

    /**
     * Define Transition
     *
     * @param DomainObject $event
     * @param  Closure $condition
     * @return StateMachineInterface
     */
    public function on(DomainObject $event, Closure $condition = null): self;

    /**
     * Add next state to custom state
     *
     * @param  DomainObject $destination
     * @param  Closure $callback do some thing after the transition
     * @return self
     */
    public function transitionTo(DomainObject $destination, Closure $callback): self;

}

