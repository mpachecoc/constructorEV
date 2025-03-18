<?php

    /**
     *  Delete a Project (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Project.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project' object
    $project = new Project($db);

    // Get project ID
    $project->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if project ID exists with 'read_single' method
    $project->read_single();

    if ($project->exists){
        // Delete Project
        if ($project->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Project Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Project Not Deleted.')
            );
        }

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID Found')
        );
    }