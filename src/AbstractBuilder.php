<?php
/**
 * Created by PhpStorm.
 * User: asanbar-pc
 * Date: 1/16/19
 * Time: 3:53 PM
 */

namespace Stater;


use stdClass;

abstract class AbstractBuilder
{

    /**
     * Create Object
     *
     * @param  array $data
     * @return AbstractObject
     */
    abstract protected function create(array $data): AbstractObject;

    /**
     * Initialize the object before creation
     *
     * @param array $data
     */
    abstract protected function init(array $data): void;

    /**
     * Set object data template
     *
     * @param  EntityInterface $template
     * @return stdClass
     */
    protected function stateObject(EntityInterface $template): stdClass
    {
        return $template->build();
    }
}