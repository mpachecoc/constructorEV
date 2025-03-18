<?php

    /**
     *  Update an APU-Workers (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuCostHrsWorker.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Workers' object
    $apu_worker = new ApuCostHrsWorker($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $apu_worker->proj_id  = $data->proj_id; // Set ID to update
    $apu_worker->apu_id  = $data->apu_id; // Set ID to update
    $apu_worker->cw_id = $data->cw_id; // Set ID to update

    // Check if APU-Workers ID exists with 'read_single' method
    $apu_worker->read_single();

    if ($apu_worker->exists){
        // Get rest of data to update
        $apu_worker->cant = $data->cant;
        $apu_worker->precio_productivo = $data->precio_productivo;
        $apu_worker->costo_total = $data->costo_total;

        // Update APU-Workers
        if ($apu_worker->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Item Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Item Not Updated.')
            );
        }

    }else{
        // No APU-Workers IDs found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }