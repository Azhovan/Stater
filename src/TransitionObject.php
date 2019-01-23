<?php

namespace Stater;

interface TransitionObject
{
    /**
     * Return the transition Object
     *
     * @return Transition|null
     */
    public function getTransitionObject(): Transition;
}