<?php

    /**
     *  Update an APU-Equipments (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuEquipment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Equipments' object
    $apu_equipment = new ApuEquipment($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $apu_equipment->proj_id  = $data->proj_id; // Set ID to update
    $apu_equipment->apu_id  = $data->apu_id; // Set ID to update
    $apu_equipment->equip_id = $data->equip_id; // Set ID to update

    // Check if APU-Equipments ID exists with 'read_single' method
    $apu_equipment->read_single();

    if ($apu_equipment->exists){
        // Get rest of data to update
        $apu_equipment->cant = $data->cant;
        $apu_equipment->precio_productivo = $data->precio_productivo;
        $apu_equipment->costo_total = $data->costo_total;

        // Update APU-Equipments
        if ($apu_equipment->update()) {
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
        // No APU-Equipments IDs found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }