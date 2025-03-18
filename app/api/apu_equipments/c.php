<?php

    /**
     *  Create an APU-Equipments (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuEquipment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Equipments' object
    $apu_equipment = new ApuEquipment($db);

    // Get raw json APU-Equipments data
    $data = json_decode(file_get_contents("php://input"));

    $apu_equipment->proj_id = $data->proj_id;
    $apu_equipment->apu_id = $data->apu_id;
    $apu_equipment->equip_id = $data->equip_id;
    $apu_equipment->cant = $data->cant;
    $apu_equipment->precio_productivo = $data->precio_productivo;
    $apu_equipment->costo_total = $data->costo_total;


    // Create APU-Equipments
    if ($apu_equipment->create()) {
        http_response_code(201);
        echo json_encode(
            array('message' => 'Item Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Item Not Created.')
        );
    }