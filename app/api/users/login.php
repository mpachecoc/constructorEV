<?php

    /**
     *  Login
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

    // Get User Credentials
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get data
    $exp = explode("&", $data);
    $user->username = $exp[0];
    $user->password = md5($exp[1]);

    // Check
    $user->login();

    if ($user->exists){
        // Create array
        $arr['data'] = array(
            'id' => $user->id,
            'username' => $user->username,
            'password' => $user->password,
            'rol' => $user->rol,
            'roles' => $user->roles,
            'date_created' => $user->date_created
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No User found
        http_response_code(404);
        echo json_encode(
            array('message' => 'Incorrect Credentials')
        );
    }
