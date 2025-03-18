<?php

    /**
     *  Delete a Patronal Input (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialPatronalInput.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Patronal Input' object
    $patronal_in = new SocialPatronalInput($db);

    // Get Patronal Input ID
    $patronal_in->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Patronal Input ID exists with 'read_single' method
    $patronal_in->read_single();

    if ($patronal_in->exists){
        // Delete Patronal Input
        if ($patronal_in->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Patronal Input Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Patronal Input Not Deleted.')
            );
        }

    }else{
        // No Patronal Input ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Patronal Input ID Found')
        );
    }