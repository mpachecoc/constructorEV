<?php

    /**
     *  Create an Equipments (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Equipment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Equipment' object
    $equipment = new Equipment($db);

    // Get raw json Equipment data
    $data = json_decode(file_get_contents("php://input"));

    $equipment->name = $data->name;
    $equipment->desc = $data->desc;
    $equipment->precio = $data->precio;


    // Create Equipment
    if ($equipment->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Equipment Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Equipment Not Created.')
        );
    }