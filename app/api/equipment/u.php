<?php

    /**
     *  Update an Equipment (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    $equipment->id = $data->id; // Set ID to update

    // Check if Equipment ID exists with 'read_single' method
    $equipment->read_single();

    if ($equipment->exists){
        // Get rest of data to update
        $equipment->name = $data->name;
        $equipment->desc = $data->desc;
        $equipment->precio = $data->precio;

        // Update Equipment
        if ($equipment->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Equipment Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Equipment Not Updated.')
            );
        }

    }else{
        // No Equipment ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Equipment ID Found')
        );
    }