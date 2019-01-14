<?php

declare(strict_types=1);

namespace Stater\State;

use Countable;
use InvalidArgumentException;
use Stater\AbstractObject;
use Stater\EntityInterface;
use Stater\Event\Entity;

class State extends AbstractObject implements Countable
{

    private $states = [];

    /**
     * @param  array $states
     * @throws InvalidArgumentException
     * @return self
     */
    public function create(array $states): self
    {
        if (empty($states)) {
            throw new InvalidArgumentException(
                "input couldn't be empty array"
            );
        }

        $this->init($states);

        return $this;
    }

    /**
     * @param  array $states
     * @return void
     */
    protected function init(array $states): void
    {
        foreach ($states as $state) {
            $this->states[$state] = $this->stateObject(new Entity($state));
        }
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->getObjects());
    }

    /**
     * @inheritdoc
     */
    public function getObjects()
    {
        return $this->states;
    }

}