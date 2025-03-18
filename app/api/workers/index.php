<?php

    /**
     *  Read all Workers
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

    // Get 'read' method
    $result = $worker->read();
    $num    = $result->rowCount();

    // Check if any Worker
    if($num > 0){
        // Worker array
        $worker_arr = array();
        $worker_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $worker_item = array(
                'id' => $workerID,
                'name' => $workerName,
                'desc' => $workerDesc
            );

            // Push to 'data'
            array_push($worker_arr['data'], $worker_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($worker_arr));

    }else{
        // No Workers
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Workers Found')
        );
    }