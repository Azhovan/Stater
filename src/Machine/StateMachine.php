<?php

namespace Stater\Machine;

use Closure;
use Stater\AbstractObject;
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
     * The destination point for transition
     *
     * @var AbstractObject
     */

    /**
     * Starting of the transition node
     *
     * @var AbstractObject
     */
    private $transition;

    /**
     * The Initial point of machine
     *
     * @var AbstractObject
     */
    private $init;

    /**
     * The end state of the machine
     *
     * @var AbstractObject
     */
    private $end;

    public function __construct(Transition $transition)
    {
        $this->transition = $transition ?? new Transition();
    }


    /**
     * Init starting point of the machine
     *
     * @param  AbstractObject $init
     * @return self
     */
    public function init(AbstractObject $init)
    {
        $this->init = $init;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function state(AbstractObject $state): StateMachineInterface
    {
        $this->transition->start($state);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function on(AbstractObject $event, Closure $condition = null): StateMachineInterface
    {
        $this->transition->event($event)
            ->condition($condition);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function transitionTo(AbstractObject $state, Closure $callback): StateMachineInterface
    {
        $this->transition->end($state)
            ->callback($callback);

        return $this->add();
    }

    /**
     * Get the full map of stateMachine
     *
     * @return mixed
     */
    public function get()
    {
        return $this->map;
    }


    private function add()
    {
        $this->map = $this->transition->get();

        $this->transition->reset();

        return $this;
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
        // TODO: Implement count() method.
    }

}