<?php

namespace Controller;

abstract class Controller implements ControllerInterface
{
    public function __construct()
    {
        include 'ControllerFunction.php';
    }
}
