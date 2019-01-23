<?php

class test
{
    public function __invoke()
    {
        print_r(func_get_args());
    }
}

$t = new test();

$t("a", "b");