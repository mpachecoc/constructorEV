<?php

    /**
     *  Read a Single APU-Equipments (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ApuEquipment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Equipments' object
    $apu_equipment = new ApuEquipment($db);

    // Get APU-Equipments ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Equip id
    $exp = explode("&", $data);
    $apu_equipment->proj_id  = $exp[0];
    $apu_equipment->apu_id  = $exp[1];
    $apu_equipment->equip_id = $exp[2];

    // Get APU-Equipments
    $apu_equipment->read_single();

    if ($apu_equipment->exists){
        // Create array
        $arr['data'] = array(
            'proj_id' => $apu_equipment->proj_id,
            'apu_id' => $apu_equipment->apu_id,
            'equip_id' => $apu_equipment->equip_id,
            'cant' => $apu_equipment->cant,
            'precio_productivo' => $apu_equipment->precio_productivo,
            'costo_total' => $apu_equipment->costo_total
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-Equipments found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }
