<?php

namespace Stater\Machine;

use Closure;
use Countable;
use IteratorAggregate;
use Stater\DomainObject;
use Stater\Transition;

interface StateMachineInterface extends IteratorAggregate, Countable
{

    /**
     * Check the feasibility of given transition on current state
     *
     * @param Transition|string $transition
     * @param array $parameters
     * @return bool
     */
    public function can($transition, array $parameters = []): bool;

    /**
     * Get/Set the current state
     *
     * @param DomainObject|null $state
     * @return DomainObject
     */
    public static function current(DomainObject $state = null): DomainObject;

    /**
     * Initialize the starting state
     *
     * @param DomainObject $init
     * @return StateMachineInterface
     */
    public function init(DomainObject $init): self;

    /**
     * Initialize the destination state
     *
     * @param DomainObject $end
     * @return StateMachineInterface
     */
    public function end(DomainObject $end): self;

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

    /**
     * Add new transition to current state machine
     *
     * @param null|Transition $transition
     * @return StateMachineInterface
     */
    public function addTransition(?Transition $transition = null): self;

}

