<?php

declare(strict_types=1);

namespace Stater\States;

use InvalidArgumentException;
use Stater\AbstractObject;

class State extends AbstractObject
{

    private $states = [];

    /**
     * @param  array $states
     * @throws InvalidArgumentException
     * @return AbstractObject
     */
    public function create(array $states): AbstractObject
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

            if ($this->exists($state)) {
                continue;
            }

            if (is_array($state)) {
                $this->states[$state["name"]] = $this->stateObject(new Entity($state));

            } else {
                $this->states[$state] = $this->stateObject(new Entity($state));
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function getObjects(): array
    {
        return $this->states;
    }

}