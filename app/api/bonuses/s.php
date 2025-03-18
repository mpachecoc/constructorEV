<?php

    /**
     *  Read a Single Bonus (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/SocialBonus.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Bonus' object
    $bonus = new SocialBonus($db);

    // Get Bonus ID
    $bonus->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Bonus
    $bonus->read_single();

    if ($bonus->exists){
        // Create array
        $bonus_arr['data'] = array(
            'id' => $bonus->id,
            'aguinaldo' => $bonus->aguinaldo,
            'subsidios' => $bonus->subsidios,
            'indemnizacion' => $bonus->indemnizacion,
            'otros' => $bonus->otros,
            'subtotal_bonos' => $bonus->subtotal
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($bonus_arr));

    }else{
        // No Bonus ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Bonus ID Found')
        );
    }
