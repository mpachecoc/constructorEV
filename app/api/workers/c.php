<?php

    /**
     *  Create a Worker (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    $worker->name = $data->name;
    $worker->desc = $data->desc;


    // Create Worker
    if ($worker->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Worker Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Worker Not Created.')
        );
    }