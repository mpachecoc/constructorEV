<?php

    /**
     *  Delete a Project Complementary (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectComplementary.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Complementary' object
    $project_comp = new ProjectComplementary($db);

    // Get project ID
    $project_comp->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if project ID exists with 'read_single' method
    $project_comp->read_single();

    if ($project_comp->exists){
        // Delete Project
        if ($project_comp->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Project Complementary Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Project Complementary Not Deleted.')
            );
        }

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID Found')
        );
    }