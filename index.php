<?php

include(__DIR__ . '/App/User.php');

try {
    header('Content-Type: text/plain');
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";
    } else {
        $url = "http://";
    }
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    $uriArray = explode('/', $url);


    if ($uriArray[3] == 'setup') {
        if ($uriArray[4] == 'run') {
            $connection = new \Database\MySQL();
            $sql = "CREATE TABLE users ( id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(40) NOT NULL, year_of_birth VARCHAR(40) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP )";
            $result = $connection->run($sql);
            if ($result) {
                echo "Users table created successfully";
            } else {
                http_response_code(503);
                echo "Users table either already created or there was an error with your request.";
            }
        } else {
            http_response_code(404);
            echo '404';
        }
    } elseif ($uriArray[3] == 'user') {
        $user = new User();
        if ($uriArray[4] == 'create') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'year_of_birth' => $_POST['year_of_birth'] ?? ''
            ];
            $response = $user->create($data);
            if (is_numeric($response)) {
                http_response_code(201);
                echo 'New user with id ' . $response . ' has been created.';
            } else {
                http_response_code(503);
                echo 'There was an error while creating user.';
            }

        } elseif ($uriArray[4] == 'update') {
            if (!is_numeric($uriArray[5])) {
                http_response_code(503);
                echo 'User ID not provided.';
            } else {
                $id = $uriArray[5];
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'year_of_birth' => $_POST['year_of_birth'] ?? ''
                ];
                $response = $user->update($id, $data);
                if ($response) {
                    echo 'User has been updated!';
                } else {
                    http_response_code(404);
                    echo 'There was an error while updating user.';
                }
            }
        } elseif ($uriArray[4] == 'read') {
            if (!is_numeric($uriArray[5])) {
                http_response_code(503);
                echo 'User ID not provided.';
            } else {
                $id = $uriArray[5];
                $response = $user->read($id);
                if ($response) {
                    echo "This is " . $response['name'] . ", born in " . $response['year_of_birth'] . ".";
                } else {
                    http_response_code(404);
                    echo 'User not found.';
                }
            }
        } elseif ($uriArray[4] == 'delete') {
            if (!is_numeric($uriArray[5])) {
                http_response_code(503);
                echo 'User ID not provided.';
            } else {
                $id = $uriArray[5];
                $response = $user->delete($id);
                if ($response) {
                    http_response_code(200);
                    echo 'User deleted successfully';
                } else {
                    http_response_code(404);
                    echo 'User with id not found.';
                }
            }
        } else {
            http_response_code(404);
            echo '404';
        }
    } else {
        http_response_code(404);
        echo '404';

    }
} catch (\Exception $exception) {
    http_response_code(503);
    echo 'There was an error processing your request.';
}