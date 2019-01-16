<?php
/**
 * Created by PhpStorm.
 * User: asanbar-pc
 * Date: 1/16/19
 * Time: 3:52 PM
 */

namespace Stater;


interface Accessor
{

    /**
     * Access to child property objects
     *
     * @return mixed
     */
    public function getObjects();

}