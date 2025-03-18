<?php

    /**
     *  Update an APU-Supplies (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuSupply.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Supplies' object
    $apu_supply = new ApuSupply($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $apu_supply->proj_id = $data->proj_id; // Set ID to update
    $apu_supply->apu_id  = $data->apu_id; // Set ID to update
    $apu_supply->supp_id = $data->supp_id; // Set ID to update

    // Check if APU-Supplies ID exists with 'read_single' method
    $apu_supply->read_single();

    if ($apu_supply->exists){
        // Get rest of data to update
        $apu_supply->cant = $data->cant;
        $apu_supply->precio_productivo = $data->precio_productivo;
        $apu_supply->costo_total = $data->costo_total;

        // Update APU-Supplies
        if ($apu_supply->update()) {
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
        // No APU-Supplies IDs found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }