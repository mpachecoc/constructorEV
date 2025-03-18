<?php

    /**
     *  Update a User (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'User' object
    $user = new User($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $user->id = $data->id; // Set ID to update

    // Check if user ID exists with 'read_single' method
    $user->read_single();

    if ($user->exists){
        // Get rest of data to update
        $user->username = $data->username;
        $user->password = $data->password;
        $user->rol   = $data->rol;
        $user->roles = $data->roles;
        $user->date_created = $data->date_created;

        // Update User
        if ($user->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'User Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'User Not Updated.')
            );
        }

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No User ID Found')
        );
    }