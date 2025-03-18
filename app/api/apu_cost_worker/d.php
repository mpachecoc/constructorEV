<?php

    /**
     *  Delete an APU-Workers (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuCostHrsWorker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Workers' object
    $apu_worker = new ApuCostHrsWorker($db);

    // Get APU-Workers ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Worker id
    $exp = explode("&", $data);
    $apu_worker->proj_id = $exp[0];
    $apu_worker->apu_id  = $exp[1];
    $apu_worker->cw_id = $exp[2];

    // Check if IDs exists with 'read_single' method
    $apu_worker->read_single();

    if ($apu_worker->exists){
        // Delete APU-Workers
        if ($apu_worker->delete()) {
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

    }else{
        // No APU-Workers ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }