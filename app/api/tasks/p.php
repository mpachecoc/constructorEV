<?php

    /**
     *  Patch (update) a Task (p)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PATCH');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Task.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Task' object
    $task = new Task($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $task->id = $data->id; // Set APU ID 
    $task->project_id = $data->project_id;

    // Check if task ID exists with 'read_single' method
    $task->read_single();

    if ($task->exists){
        
        // Get row and value to patch
        foreach($data as $key => $val) {
            if($key != 'id' && $key != 'project_id'){
                $task->row_to_patch = $key;
                $task->val_to_patch = $val;
            }
        }

        // Patch (update)
        if ($task->update_single()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Value Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Value Not Updated.')
            );
        }

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Task IDs Found')
        );
    }