<?php
/**
 * Created by PhpStorm.
 * User: asanbar-pc
 * Date: 1/14/19
 * Time: 1:43 PM
 */

namespace Stater\State;


abstract class AbstractObjectFactory
{
    public function create(array $data)
    {
        return $this->getObject($data);
    }

    abstract protected function getObject(array $data);
}