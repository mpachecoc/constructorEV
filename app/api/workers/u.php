<?php

    /**
     *  Update a Worker (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Worker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Worker' object
    $worker = new Worker($db);

    // Get raw json Worker data
    $data = json_decode(file_get_contents("php://input"));

    $worker->id = $data->id; // Set ID to update

    // Check if Worker ID exists with 'read_single' method
    $worker->read_single();

    if ($worker->exists){
        // Get rest of data to update
        $worker->name = $data->name;
        $worker->desc = $data->desc;

        // Update Worker
        if ($worker->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Worker Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Worker Not Updated.')
            );
        }

    }else{
        // No Worker ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Worker ID Found')
        );
    }