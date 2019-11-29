<?php

/**
 * @Inject user
 */
class View
{
    public function __construct()
    {
    }

    public function show() {
        echo $this->user->getId();
        echo "\n\r";
        echo $this->user->getName();
        echo "\n\r";
        echo $this->user->getAge();
        echo "\n\r";

    }
}