<?php

    /**
     *  Read Tasks by Project (by_project)
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

    // Get Project ID
    $task->project_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Tasks
    $result = $task->read_by_project();
    $num    = $result->rowCount();

    if($num > 0){
        // Tasks array
        $task_arr = array();
        $task_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $task_item = array(
                'id' => $apuID,
                'project_id' => $projID,
                'name' => $taskName,
                'percentage' => $taskPercentageComp,
                'start_date' => $taskStart,
                'end_date' => $taskEnd
            );

            // Push to 'data'
            array_push($task_arr['data'], $task_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($task_arr));

    }else{
        // No Tasks
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Tasks Found')
        );
    }
