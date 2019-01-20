<?php

namespace Stater\Machine;

use Closure;
use Countable;
use IteratorAggregate;
use Serializable;
use Stater\AbstractObject;

interface StateMachineInterface extends IteratorAggregate, Countable, Serializable
{

    public const state = [
        "ACTIVE" => 1,
        "DEACTIVE" => 0,
        "START" => "start",
        "END" => "end"
    ];

    /**
     * Create State and attached it to State Machine
     *
     * @param  AbstractObject $start
     * @return StateMachineInterface
     */
    public function state(AbstractObject $start): self;

    /**
     * Define Transition
     *
     * @param  AbstractObject $event
     * @param  Closure        $condition
     * @return StateMachineInterface
     */
    public function on(AbstractObject $event, Closure $condition = null): self;

    /**
     * Add next state to custom state
     *
     * @param  AbstractObject $destination
     * @param  Closure        $callback    do some thing after the transition
     * @return self
     */
    public function transitionTo(AbstractObject $destination, Closure $callback): self;

}

