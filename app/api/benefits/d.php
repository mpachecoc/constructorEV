<?php

    /**
     *  Delete a Benefit (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialBenefits.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Benefits' object
    $benefit = new SocialBenefits($db);

    // Get Benefit ID
    $benefit->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Benefit ID exists with 'read_single' method
    $benefit->read_single();

    if ($benefit->exists){
        // Delete Benefit
        if ($benefit->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Benefit Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Benefit Not Deleted.')
            );
        }

    }else{
        // No Benefit ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Benefit ID Found')
        );
    }