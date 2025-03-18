<?php

    /**
     *  Update a Task (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Task.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Task' object
    $task = new Task($db);

    // Get raw json Task data
    $data = json_decode(file_get_contents("php://input"));

    $task->id = $data->id; // Set APU ID 
    $task->project_id = $data->project_id;

    // Check if Task ID exists with 'read_single' method
    $task->read_single();

    if ($task->exists){
        // Get rest of data to update
        $task->name = $data->name;
        $task->percentage = $data->percentage;
        $task->start_date = $data->start_date;
        $task->end_date = $data->end_date;

        // Update Task
        if ($task->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Task Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Task Not Updated.')
            );
        }

    }else{
        // No Task ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Task ID Found')
        );
    }