<?php

    /**
     *  Delete a Worker (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Worker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Worker' object
    $worker = new Worker($db);

    // Get Worker ID
    $worker->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Worker ID exists with 'read_single' method
    $worker->read_single();

    if ($worker->exists){
        // Delete Worker
        if ($worker->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Worker Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Worker Not Deleted.')
            );
        }

    }else{
        // No Worker ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Worker ID Found')
        );
    }