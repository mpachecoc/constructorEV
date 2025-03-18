<?php

    /**
     *  Read all Users
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

    // Get 'read' method
    $result = $user->read();
    $num    = $result->rowCount();

    // Check if any users
    if($num > 0){
        // User array
        $user_arr = array();
        $user_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $user_item = array(
                'id' => $userID,
                'username' => $userName,
                'password' => $userPass,
                'rol'   => $userRole,
                'roles' => $userRoles,
                'date_created' => $userDateIn
            );

            // Push to 'data'
            array_push($user_arr['data'], $user_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($user_arr));

    }else{
        // No Users
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Users Found')
        );
    }