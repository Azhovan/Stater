<?php

require __DIR__ . "/vendor/autoload.php";

// Create simple state machine with single entry
$machine = Stater\Machine\Factory::create([
        [
            "state" => ["name" => "state1",],
            "event" => ["name" => "event1", "data" => ""],
            "condition" => function () {
                return true;
            },
            "transitionTo" => ["name" => "state2", "data" => ""],
            "callback" => function () {
                // code here
            }
        ]
    ]
);