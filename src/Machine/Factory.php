<?php

namespace Stater\Machine;

use Closure;
use Stater\Event\EventInterface;
use Stater\State\StateInterface;

interface Factory
{

    /**
     * Create State and attached it to State Machin
     *
     * @param  StateInterface $state
     * @param  EventInterface $event
     * @return Factory
     */
    public function state(StateInterface $state, EventInterface $event): self;

    /**
     * Define Transition
     *
     * @param  EventInterface $event
     * @param  Closure        $closure
     * @return Factory
     */
    public function on(EventInterface $event, Closure $closure): self;

    /**
     * Add next state to custom state
     *
     * @param  StateInterface $state
     * @return Factory
     */
    public function transitionTo(StateInterface $state): self;

    /**
     * Action after transition
     *
     * @param  Closure $closure
     * @return Factory
     */
    public function promise(Closure $closure): self;


    /**
     * @param  $value
     * @return Factory
     */
    public function resolve($value): self;


    /**
     * Print whole state machine
     *
     * @param  bool $table
     * @return string
     */
    public function print($table = false): string;

}

