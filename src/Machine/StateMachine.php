<?php

namespace Stater\Machine;

use Closure;
use Stater\AbstractObject;

class StateMachine implements StateMachineInterface
{

    /**
     * The starting point for transition
     *
     * @var AbstractObject
     */

    private $start;

    /**
     * The destination point for transition
     *
     * @var AbstractObject
     */

    private $destination;

    /**
     * The Initial point of machine
     *
     * @var AbstractObject
     */
    private $init;

    /**
     * The Needed event to go to next state
     *
     * @var
     */
    private $event;


    public function __construct(AbstractObject $init)
    {
        $this->init = $init;
    }

    /**
     * @inheritdoc
     */
    public function state(AbstractObject $start): StateMachineInterface
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function on(AbstractObject $event, Closure $condition = null): StateMachineInterface
    {

        if ($condition instanceof Closure) {

            $this->event->condition = $condition;
        }

        $this->event = $event;

        return $this;

    }

    /**
     * @inheritdoc
     */
    public function transitionTo(AbstractObject $destination, Closure $callback): StateMachineInterface
    {

        if ($callback instanceof Closure) {

            $this->destination->callback = $callback;
        }

        $this->destination = $destination;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function promise(Closure $closure): self
    {
        // TODO: Implement promise() method.
    }

    /**
     * @inheritdoc
     */
    public function resolve($value): self
    {
        // TODO: Implement resolve() method.
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