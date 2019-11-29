<?php

/*
 * @Inject user
 */
class View
{
    public function __construct()
    {
        echo "View Constructor";
    }

    public function show() {
        echo $this->user->getId();
        echo $this->user->getName();
        echo $this->user->getAge();
    }
}