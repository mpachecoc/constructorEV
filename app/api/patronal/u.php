<?php

    /**
     *  Update a Patronal Input (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialPatronalInput.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Patronal Input' object
    $patronal_in = new SocialPatronalInput($db);

    // Get raw json patronal input data
    $data = json_decode(file_get_contents("php://input"));

    $patronal_in->id = $data->id; // Set ID to update

    // Check if patronal input ID exists with 'read_single' method
    $patronal_in->read_single();

    if ($patronal_in->exists){
        // Get rest of data to update
        $patronal_in->cnss = $data->cnss;
        $patronal_in->infocal = $data->infocal;
        $patronal_in->aporte_vivencia = $data->aporte_vivencia;
        $patronal_in->afps = $data->afps;
        $patronal_in->subtotal = $data->subtotal_ap;
        $patronal_in->equivalente_dc = $data->equivalente_dc;

        // Update patronal input
        if ($patronal_in->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Patronal Input Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Patronal Input Not Updated.')
            );
        }

    }else{
        // No Patronal Input ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Patronal Input ID Found')
        );
    }