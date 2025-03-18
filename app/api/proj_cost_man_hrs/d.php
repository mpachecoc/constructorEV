<?php

    /**
     *  Delete a Project Cost Man Hrs. (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjCostManHrsData.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Cost Man Hrs' object
    $proj_cmh = new ProjCostManHrsData($db);

    // Get project ID
    $proj_cmh->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if project ID exists with 'read_single' method
    $proj_cmh->read_single();

    if ($proj_cmh->exists){
        // Delete Project (Cost Man Hrs.)
        if ($proj_cmh->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Project (Cost Man Hrs.) Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Project (Cost Man Hrs.) Not Deleted.')
            );
        }

    }else{
        // No Project ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID (Cost Man Hrs.) Found')
        );
    }