<?php

    /**
     *  Read a Single User (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'User' object
    $user = new User($db);

    // Get user ID
    $user->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    //$user->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get user
    $user->read_single();

    if ($user->exists){
        // Create array
        $user_arr['data'] = array(
            'id' => $user->id,
            'username' => $user->username,
            'password' => $user->password,
            'rol'   => $user->rol,
            'roles' => $user->roles,
            'date_created' => $user->date_created
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($user_arr));

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No User ID Found')
        );
    }

