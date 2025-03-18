<?php

    /**
     *  Read a Single Supply (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Supplies.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Supplies' object
    $supplies = new Supplies($db);

    // Get supply ID
    $supplies->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Supply
    $supplies->read_single();

    if ($supplies->exists){
        // Create array
        $supplies_arr['data'] = array(
            'id'   => $supplies->id,
            'name' => $supplies->name,
            'desc' => $supplies->desc,
            'und'  => $supplies->und,
            'precio' => $supplies->precio,
            'moneda' => $supplies->moneda
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($supplies_arr));

    }else{
        // No Supply ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Supply ID Found')
        );
    }
