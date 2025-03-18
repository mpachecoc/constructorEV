<?php

    /**
     *  Delete a Supply (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Supplies.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Supplies' object
    $supplies = new Supplies($db);

    // Get supply ID
    $supplies->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if supply ID exists with 'read_single' method
    $supplies->read_single();

    if ($supplies->exists){
        // Delete Supply
        if ($supplies->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Supply Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Supply Not Deleted.')
            );
        }

    }else{
        // No Supply ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Supply ID Found')
        );
    }