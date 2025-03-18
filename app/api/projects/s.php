<?php

    /**
     *  Read a Single Project (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Project.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project' object
    $project = new Project($db);

    // Get project ID
    $project->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get project
    $project->read_single();

    if ($project->exists){
        // Create array
        $project_arr['data'] = array(
            'id' => $project->id,
            'name' => $project->name,
            'date_created' => $project->date_created,
            'created_by'   => $project->created_by,
            'location' => $project->location,
            'elaboration_date' => $project->elaboration_date,
            'presup_date' => $project->presup_date,
            'end_date' => $project->end_date,
            'calendar_days' => $project->calendar_days,
            'contract_num' => $project->contract_num,
            'duration' => $project->duration,
            'currency' => $project->currency,
            'exchange_rate' => $project->exchange_rate
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($project_arr));

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID Found')
        );
    }
