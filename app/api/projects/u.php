<?php

    /**
     *  Update a Project (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    date_default_timezone_set('America/La_Paz');

    include_once '../../config/Database.php';
    include_once '../../models/Project.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project' object
    $project = new Project($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $project->id = $data->id; // Set ID to update

    // Check if project ID exists with 'read_single' method
    $project->read_single();

    if ($project->exists){
        // Get rest of data to update
        $project->name = $data->name;
        $project->date_created = date("Y-m-d");
        $project->created_by   = $data->created_by;
        $project->location = $data->location;
        list($d,$m,$y) = sscanf($data->elaboration_date, "%d-%d-%d");
        $project->elaboration_date = $y."-".$m."-".$d;
        list($d,$m,$y) = sscanf($data->presup_date, "%d-%d-%d");
        $project->presup_date = $y."-".$m."-".$d;
        list($d,$m,$y) = sscanf($data->end_date, "%d-%d-%d");
        $project->end_date = $y."-".$m."-".$d;  
        $project->calendar_days = $data->calendar_days;
        $project->contract_num = $data->contract_num;
        $project->duration = $data->duration;
        $project->currency = $data->currency;
        $project->exchange_rate = $data->exchange_rate;

        // Update Project
        if ($project->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Project Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Project Not Updated.')
            );
        }

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID Found')
        );
    }