<?php

    /**
     *  Delete a User (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'User' object
    $user = new User($db);

    // Get user ID
    $user->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if user ID exists with 'read_single' method
    $user->read_single();

    if ($user->exists){
        // Delete User
        if ($user->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'User Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'User Not Deleted.')
            );
        }

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No User ID Found')
        );
    }