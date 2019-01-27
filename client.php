<?php

require __DIR__ . "/vendor/autoload.php";

// Create simple state machine with single entry
$machine = Stater\Machine\Factory::create(
    [
        "state" => [
            "name" => "state_1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ],
        "event" => [
            "name" => "event_name_1",
            "data" => [
                "user" => "jabar_asadi_1",
                "email" => "asadi.jabar@outlook.com",
                "credit" => "10000",
                "availability" => "1-march",
                "vip" => true
            ]
        ],
        "condition" => function () {
        },
        "callback" => function () {
        },
        "transitionTo" => [
            "name" => "state_1",
            "data" => [
                "user" => "test_user1",
                "credit" => "250"
            ]
        ]

    ]
);