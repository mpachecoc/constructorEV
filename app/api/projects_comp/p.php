<?php

    /**
     *  Patch (update) a Project Complementary (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PATCH');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectComplementary.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Complementary' object
    $project_comp = new ProjectComplementary($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $project_comp->id = $data->id; // Set ID to update

    // Check if project ID exists with 'read_single' method
    $project_comp->read_single();

    if ($project_comp->exists){
        
        // Get row and value to patch
        foreach($data as $key => $val) {
            if($key != 'id'){
                $project_comp->row_to_patch = $key;
                $project_comp->val_to_patch = $val;
            }
        }

        // Patch (update)
        if ($project_comp->update_single()) {
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
            array('message' => 'No Project ID Found')
        );
    }