<?php

    /**
     *  Delete an APU (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Apu.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU' object
    $apu = new Apu($db);

    // Get APU ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get Proj id & APU id
    $exp = explode("&", $data);
    $apu->project_id = $exp[0];
    $apu->id = $exp[1];

    // Check if APU and Proj ID exist with 'read_single' method
    $apu->read_single();

    if ($apu->exists){
        // Delete APU
        if ($apu->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'APU Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'APU Not Deleted.')
            );
        }

    }else{
        // No APU ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No APU or Project ID Found')
        );
    }