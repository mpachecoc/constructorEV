<?php

    /**
     *  Create a User (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    date_default_timezone_set('America/La_Paz');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'User' object
    $user = new User($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $user->username = $data->username;
    $user->password = $data->password;
    $user->rol   = $data->rol;
    $user->roles = $data->roles;
    $user->date_created = date("Y-m-d");


    // Create User
    if ($user->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'User Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'User Not Created.')
        );
    }