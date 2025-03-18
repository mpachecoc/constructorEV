<?php

    /**
     *  Read a Single Equipments (s)
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

    // Get Equipment ID
    $equipment->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Equipment
    $equipment->read_single();

    if ($equipment->exists){
        // Create array
        $equipment_arr['data'] = array(
            'id'   => $equipment->id,
            'name' => $equipment->name,
            'desc' => $equipment->desc,
            'precio' => $equipment->precio
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($equipment_arr));

    }else{
        // No Equipment ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Equipment ID Found')
        );
    }
