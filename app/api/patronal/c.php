<?php

    /**
     *  Create a Patronal Input (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialPatronalInput.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Patronal Input' object
    $patronal_in = new SocialPatronalInput($db);

    // Get raw json Patronal Input data
    $data = json_decode(file_get_contents("php://input"));

    $patronal_in->id = $data->id;
    $patronal_in->cnss = $data->cnss;
    $patronal_in->infocal = $data->infocal;
    $patronal_in->aporte_vivencia = $data->aporte_vivencia;
    $patronal_in->afps = $data->afps;
    $patronal_in->subtotal = $data->subtotal_ap;
    $patronal_in->equivalente_dc = $data->equivalente_dc;


    // Create Patronal Input
    if ($patronal_in->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Patronal Input Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Patronal Input Not Created.')
        );
    }