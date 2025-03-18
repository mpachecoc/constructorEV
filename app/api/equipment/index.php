<?php

    /**
     *  Read all Equipments
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Equipment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Equipment' object
    $equipment = new Equipment($db);

    // Get 'read' method
    $result = $equipment->read();
    $num    = $result->rowCount();

    // Check if any equipment
    if($num > 0){
        // Equipment array
        $equipment_arr = array();
        $equipment_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $equipment_item = array(
                'id' => $equipID,
                'name' => $equipName,
                'desc' => $equipDesc,
                'precio' => $equipPrecio
            );

            // Push to 'data'
            array_push($equipment_arr['data'], $equipment_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($equipment_arr));

    }else{
        // No equipments
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Equipment Found')
        );
    }