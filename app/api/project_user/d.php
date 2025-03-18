<?php

    /**
     *  Delete a Project-User (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectUser.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project-User' object
    $proj_user = new ProjectUser($db);

    // Get IDs
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get Project and User IDs
    $exp = explode("&", $data);
    $proj_user->user_id  = $exp[0];
    $proj_user->project_id = $exp[1];

    // Check if Project-User ID exists with 'read_single' method
//    $proj_user->read_single();

//    if ($proj_user->exists){
        // Delete Project-User
        if ($proj_user->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Item Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Item Not Deleted.')
            );
        }

//    }else{
         // No Project - User IDs found
//        http_response_code(404);
//        echo json_encode(
//            array('message' => 'No IDs Found')
//        );
//    }