<?php

    /**
     *  Delete a Cost Man Hrs. Worker (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/CostManHrsWorker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Cost Man Hrs. Worker' object
    $worker = new CostManHrsWorker($db);

    // Get ID
    $worker->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if ID exists with 'read_single' method
    $worker->read_single();

    if ($worker->exists){
        // Delete
        if ($worker->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Item Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Item Not Deleted.')
            );
        }

    }else{
        // No ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No ID Found')
        );
    }