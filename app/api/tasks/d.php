<?php

    /**
     *  Delete a Task (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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

    // Check if Task exist with 'read_single' method
    $task->read_single();

    if ($task->exists){
        // Delete Task
        if ($task->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Task Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Task Not Deleted.')
            );
        }

    }else{
        // No Task ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Task ID Found')
        );
    }