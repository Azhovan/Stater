<?php

namespace Stater\Machine;

use Closure;
use Stater\DomainObject;
use Stater\EntityInterface;
use Stater\Transition;

class StateMachine implements StateMachineInterface
{

    /**
     * Hold all the hash map of state machine
     *
     * @var array
     */
    private $map = [];

    /**
     * Starting of the transition node
     *
     * @var EntityInterface
     */
    private $transition;

    /**
     * The Initial point of machine
     *
     * @var EntityInterface
     */
    private $init;

    /**
     * The end state of the machine
     *
     * @var EntityInterface
     */
    private $end;

    /**
     * Construct state machine by Transition object
     *
     * StateMachine constructor.
     *
     * @param Transition $transition
     */
    public function __construct(?Transition $transition)
    {
        $this->transition = $transition ?? new Transition();
    }

    /**
     * Initialize the starting point of the machine
     *
     * @param DomainObject $init
     * @return self
     */
    public function init(DomainObject $init)
    {
        $this->init = $init;

        return $this;
    }

    /**
     * Initialize the ending point of the machine
     *
     * @param DomainObject $end
     * @return self
     */
    public function end(DomainObject $end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function state(DomainObject $state): StateMachineInterface
    {
        $this->transition->start($state);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function on(DomainObject $event, Closure $condition = null): StateMachineInterface
    {
        $this->transition->event($event)
            ->condition($condition);

        return $this;
    }

    /**
     * siose
     *
     * @inheritdoc
     */
    public function transitionTo(DomainObject $state, Closure $callback): StateMachineInterface
    {
        $this->transition->end($state)
            ->callback($callback);

        return $this->add();
    }

    /**
     * Add transition to State machine
     *
     * @param  Transition|null $transition
     * @return self
     */
    public function add(?Transition $transition = null): self
    {
        $this->map = $transition ? $transition->get() : $this->transition->get();

        return $this;
    }

    /**
     * Get the full map of stateMachine
     *
     * @return array
     */
    public function get(): array
    {
        return $this->map;
    }

    /**
     * Renew the transition object
     *
     * @return $this
     */
    public function build(): self
    {
        $this->transition = new Transition();

        return $this;
    }

    /**
     * @return null|Transition
     */
    public function getTransitionObject()
    {
        return $this->transition;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->map);
    }

}