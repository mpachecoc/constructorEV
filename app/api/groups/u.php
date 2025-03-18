<?php

    /**
     *  Update a Group (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Group' object
    $group = new Group($db);

    // Get raw json Group data
    $data = json_decode(file_get_contents("php://input"));

    $group->id = $data->id; // Set ID to update

    // Check if group ID exists with 'read_single' method
    $group->read_single();

    if ($group->exists){
        // Get rest of data to update
        $group->name = $data->name;
        $group->project_id = $data->project_id;

        // Update Group
        if ($group->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Group Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Group Not Updated.')
            );
        }

    }else{
        // No Group ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Group ID Found')
        );
    }