<?php

include_once "DI.php";


DI::mapClass("user", "User", [
    "id" => 1,
    "name" => "Maxence",
    "age" => 22,
]);


$view = DI::getInstanceOf("View");


echo "\*********** Result **************\ \r\n ";

$view->show();

echo "\*********** DEBUG **************\ \r\n ";

var_dump(DI::getMap());
