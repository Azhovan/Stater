<?php
declare(strict_types=1);

namespace Stater\Machine;

use Closure;
use Stater\{
    DomainObject, EntityInterface, Transition, TransitionObject
};
use Stater\Traits\{
    DecorateData, InstanceFinder
};

/**
 *
 * Class StateMachine
 * @package Stater\Machine
 */
class StateMachine implements StateMachineInterface, Map, TransitionObject
{

    use InstanceFinder, DecorateData;

    /**
     * Hold all the hash map of state machine
     *
     * @var array
     */
    private $map = [];

    /*
     * Transition per cell
     *
     * @var Transition
     */
    private $transition = null;

    /**
     * Handle Transition behaviour
     *
     * @var Transition
     */
    private $transitionObject;

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
     * Decide to go to next state
     *
     * @var bool
     */
    private $moveTo = null;

    /**
     * Construct state machine by Transition object
     *
     * StateMachine constructor.
     *
     * @param null|Transition $transitionObject
     */
    public function __construct(?Transition $transitionObject)
    {
        $this->transitionObject = $transitionObject ?? new Transition();
    }

    /**
     *
     * @inheritdoc
     */
    public function can($initial, $next, array $parameters = []): bool
    {
        return $this->move($initial, $next)->with($parameters);

        // stateMachine::can(new Transition($data), [...data...]) ;
        //if (!$this->object($next)->instanceOf(Transition::class)) {
        //
        //}
    }

    /**
     * Use parameters to change the state of state machine
     *
     * @param array $parameters
     * @return bool
     */
    public function with(array $parameters = [])
    {
        if (null == $this->moveTo || null == $this->transition) {
            return false;
        }

        $decorated = $this->decorate($parameters, [
            "condition" => "array",
        ]);

        // the better call is $this->transition->condition()->with(...)
        if (!$this->transition->conditionWith($decorated['condition'])) {
            return false;
        }

        return $this->apply($decorated['callback']);
    }

    /**
     * Apply changes to state machine
     *
     * @param $callback
     * @return mixed
     */
    private function apply($callback)
    {
        $result = $this->transition->callback($callback);

        $this->moveTo = null;
        $this->transition = null;

        return (bool)$result;
    }

    /**
     * Could to go to next state
     *
     * @param $initial
     * @param string $next
     * @return StateMachine
     */
    private function move($initial, string $next)
    {
        $map = $this->getMap();

        if (!isset($map[$initial][$next])) {
            $this->moveTo = false;

        } else {
            $this->moveTo = $next;
            $this->transition = $map[$initial][$next];
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @inheritdoc
     */
    public function getTransitionObject(): Transition
    {
        return $this->transitionObject;
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
        $this->transitionObject->start($state);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function on(DomainObject $event, Closure $condition = null): StateMachineInterface
    {
        $this->transitionObject->event($event)
            ->condition($condition);

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function transitionTo(DomainObject $state, Closure $callback = null): StateMachineInterface
    {
        $this->transitionObject->end($state)
            ->callback($callback);

        return $this->addTransition();
    }

    /**
     *
     * @inheritdoc
     */
    public function addTransition(?Transition $transition = null): StateMachineInterface
    {
        $this->map = $this->transitionObject->get();
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
        $this->transitionObject = new Transition();

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
}