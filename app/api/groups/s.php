<?php

    /**
     *  Read a Single Group (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Group' object
    $group = new Group($db);

    // Get Group ID
    $group->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Group
    $group->read_single();

    if ($group->exists){
        // Create array
        $group_arr['data'] = array(
            'id'   => $group->id,
            'name' => $group->name,
            'desc' => $group->project_id
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($group_arr));

    }else{
        // No Group ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Group ID Found')
        );
    }
