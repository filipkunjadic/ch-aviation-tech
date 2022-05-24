<?php

include 'App/User.php';

$user = new \User();
$data = [];
$created = $user->update($_GET['id'],['name' => $_POST['name'] ?? '', 'year_of_birth' => $_POST['year_of_birth'] ?? '']);
if ($created) {
    echo 'User update successfully!';
} else {
    echo 'There was an error while updating user!';
}

