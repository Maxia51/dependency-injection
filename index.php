<?php

include_once "DI.php";


DI::mapClass("user", "User", [
    "id" => 1,
    "name" => "Maxence",
    "age" => 22,
]);

DI::mapClass("view", "View");

$view = DI::getInstanceOf("View");

$view->show();

var_dump(DI::getMap());

// DI::mapClass("view", "View");
