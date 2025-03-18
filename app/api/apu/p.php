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
    include_once '../../models/Apu.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU' object
    $apu = new Apu($db);

    // Get raw json apu data
    $data = json_decode(file_get_contents("php://input"));

    $apu->id = $data->id; // Set APU ID 
    $apu->project_id = $data->project_id;

    // Check if apu ID exists with 'read_single' method
    $apu->read_single();

    if ($apu->exists){
        
        // Get row and value to patch
        foreach($data as $key => $val) {
            if($key != 'id' && $key != 'project_id'){
                $apu->row_to_patch = $key;
                $apu->val_to_patch = $val;
            }
        }

        // Patch (update)
        if ($apu->update_single()) {
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
        // No IDs found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }