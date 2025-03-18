<?php

    /**
     *  Delete a Group (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Group' object
    $group = new Group($db);

    // Get Group ID
    $group->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Group ID exists with 'read_single' method
    $group->read_single();

    if ($group->exists){
        // Delete Group
        if ($group->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Group Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Group Not Deleted.')
            );
        }

    }else{
        // No Group ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Group ID Found')
        );
    }