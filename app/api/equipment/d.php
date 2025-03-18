<?php

    /**
     *  Delete an Equipment (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Equipment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Equipment' object
    $equipment = new Equipment($db);

    // Get Equipment ID
    $equipment->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Equipment ID exists with 'read_single' method
    $equipment->read_single();

    if ($equipment->exists){
        // Delete Equipment
        if ($equipment->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Equipment Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Equipment Not Deleted.')
            );
        }

    }else{
        // No Equipment ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Equipment ID Found')
        );
    }