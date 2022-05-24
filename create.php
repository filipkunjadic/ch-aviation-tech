<?php

include 'App/User.php';

$user = new \User();
$created = $user->create(['name' => $_POST['name'], 'year_of_birth' => $_POST['year_of_birth']]);
if ($created) {
    echo 'User created successfully!';
} else {
    echo 'There was an error while creating user!';
}

