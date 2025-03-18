<?php

    /**
     *  Read a Single Patronal Input (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/SocialPatronalInput.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Patronal Input' object
    $patronal_in = new SocialPatronalInput($db);

    // Get Patronal Input ID
    $patronal_in->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Patronal Input
    $patronal_in->read_single();

    if ($patronal_in->exists){
        // Create array
        $patronal_arr['data'] = array(
            'id' => $patronal_in->id,
            'cnss' => $patronal_in->cnss,
            'infocal' => $patronal_in->infocal,
            'aporte_vivencia' => $patronal_in->aporte_vivencia,
            'afps' => $patronal_in->afps,
            'subtotal_ap' => $patronal_in->subtotal,
            'equivalente_dc' => $patronal_in->equivalente_dc
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($patronal_arr));

    }else{
        // No Patronal Input ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Patronal Input ID Found')
        );
    }
