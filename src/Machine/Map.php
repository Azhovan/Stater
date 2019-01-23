<?php

namespace Stater\Machine;

interface Map
{
    /**
     * Return the hash table of state machine
     *
     * @return array
     */
    public function getMap(): array;
}