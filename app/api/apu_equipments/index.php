<?php

    /**
     *  Read all APU-Equipments
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

    // Get 'read' method
    $result = $apu_equipment->read();
    $num    = $result->rowCount();

    // Check if any APU-Equipments
    if($num > 0){
        // Array
        $arr = array();
        $arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $item = array(
                'proj_id' => $projID,
                'apu_id' => $apuID,
                'equip_id' => $equipID,
                'cant' => $apuEquipCant,
                'precio_productivo' => $apuEquipPrecioProductivo,
                'costo_total' => $apuEquipCostoTotal
            );

            // Push to 'data'
            array_push($arr['data'], $item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-Equipments
        http_response_code(404);
        echo json_encode(
            array('message' => 'No APU-Equipments Found')
        );
    }