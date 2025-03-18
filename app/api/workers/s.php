<?php

    /**
     *  Read a Single Worker (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Worker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Worker' object
    $worker = new Worker($db);

    // Get Worker ID
    $worker->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Worker
    $worker->read_single();

    if ($worker->exists){
        // Create array
        $worker_arr['data'] = array(
            'id'   => $worker->id,
            'name' => $worker->name,
            'desc' => $worker->desc
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($worker_arr));

    }else{
        // No Worker ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Worker ID Found')
        );
    }
