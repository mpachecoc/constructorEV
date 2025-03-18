<?php

    /**
     *  Read a Single Task (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Task.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Task' object
    $task = new Task($db);

    // Get Task ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get Proj id & APU id
    $exp = explode("&", $data);
    $task->project_id = $exp[0];
    $task->id = $exp[1];

    // Get Task
    $task->read_single();

    if ($task->exists){
        // Create array
        $task_arr['data'] = array(
            'id' => $task->id,
            'project_id' => $task->project_id,
            'name' => $task->name,
            'percentage' => $task->percentage,
            'start_date' => $task->start_date,
            'end_date' => $task->end_date
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($task_arr));

    }else{
        // No Task ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Task ID Found')
        );
    }
