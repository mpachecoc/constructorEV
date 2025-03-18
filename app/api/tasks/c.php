<?php

    /**
     *  Create a Task (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    $task->id = $data->id;
    $task->project_id = $data->project_id;
    $task->name = $data->name;
    $task->percentage = $data->percentage;
    $task->start_date = $data->start_date;
    $task->end_date = $data->end_date;

    // Create Task
    if ($task->create()) {
        http_response_code(201);
        $task_arr['data'] = array(
            'id' => $task->id,
            'project_id' => $task->project_id,
            'name' => $task->name,
            'percentage' => $task->percentage,
            'start_date' => $task->start_date,
            'end_date'  => $task->end_date
        );
        print_r(json_encode($task_arr));
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Task Not Created.')
        );
    }