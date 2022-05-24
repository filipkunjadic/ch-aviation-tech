<?php

include 'App/User.php';

$user = new \User();
if(isset($_GET['id'])) {
    echo json_encode($user->read($_GET['id']));
} else{
    
}

