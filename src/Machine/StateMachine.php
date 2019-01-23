<?php

namespace Stater\Machine;

use Closure;
use Stater\DomainObject;
use Stater\EntityInterface;
use Stater\Traits\InstanceFinder;
use Stater\Transition;
use Stater\TransitionObject;

/**
 *
 * TODO: refactor the name of class to Machine
 * Class StateMachine
 * @package Stater\Machine
 */
class StateMachine implements StateMachineInterface, Map, TransitionObject
{

    use InstanceFinder;

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
     *
     * @inheritdoc
     */
    public function can($transition, array $parameters = []): bool
    {
        // is it possible to go to the state of reject
        # stateMachine::can('reject');

        // is it possible to do below transition in this state machine ?
        # stateMachine::can(new Transition($data), [...data...]) ;

        // if the $transition is not an object of transition class
        // create an transition object with current state

        //$transition =  $this->_b($transition)->with($parameters);// check the given transition

        if (!$this->object($transition)->instanceOf(Transition::class)) {
            $transition = $this->getTransitionObject()->make()->with($transition);
        }


        $current = $this->current();
        $this->_a($transition, $current)->with($parameters);
    }


    /**
     * @inheritdoc
     */
    public function getTransitionObject(): Transition
    {
        return $this->transition;
    }

    /**
     * Get/Set the current state
     *
     * @param DomainObject|null $state
     * @return DomainObject
     */
    public static function current(DomainObject $state = null): DomainObject
    {
        // TODO: Implement current() method.
    }

    /**
     *
     * @inheritdoc
     */
    public function init(DomainObject $init): StateMachineInterface
    {
        $this->init = $init;

        return $this;
    }

    /**
     *
     * @inheritdoc
     */

    public function end(DomainObject $end): StateMachineInterface
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
     *
     * @inheritdoc
     */
    public function transitionTo(DomainObject $state, Closure $callback = null): StateMachineInterface
    {
        $this->transition->end($state)
            ->callback($callback);

        return $this->addTransition();
    }

    /**
     *
     * @inheritdoc
     */
    public function addTransition(?Transition $transition = null): StateMachineInterface
    {
        $this->map = $this->transition->get();
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
    public function reset(): self
    {
        $this->transition = new Transition();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->map);
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return count($this->map, COUNT_RECURSIVE);
    }

    /**
     * @inheritdoc
     */
    public function getMap(): array
    {
        return $this->map;
    }
}