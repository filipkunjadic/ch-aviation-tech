<?php

include 'App/User.php';

$user = new \User();
var_dump($user->delete(2));

