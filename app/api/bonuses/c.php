<?php

    /**
     *  Create a Bonus (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialBonus.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Bonus' object
    $bonus = new SocialBonus($db);

    // Get raw json Bonus data
    $data = json_decode(file_get_contents("php://input"));

    $bonus->id = $data->id;
    $bonus->aguinaldo = $data->aguinaldo;
    $bonus->subsidios = $data->subsidios;
    $bonus->indemnizacion = $data->indemnizacion;
    $bonus->otros = $data->otros;
    $bonus->subtotal = $data->subtotal_bonos;


    // Create Bonus
    if ($bonus->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Bonuses Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Bonuses Not Created.')
        );
    }