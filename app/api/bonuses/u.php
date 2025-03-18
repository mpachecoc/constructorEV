<?php

    /**
     *  Update a Bonus (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    $bonus->id = $data->id; // Set ID to update

    // Check if Bonus ID exists with 'read_single' method
    $bonus->read_single();

    if ($bonus->exists){
        // Get rest of data to update
        $bonus->aguinaldo = $data->aguinaldo;
        $bonus->subsidios = $data->subsidios;
        $bonus->indemnizacion = $data->indemnizacion;
        $bonus->otros = $data->otros;
        $bonus->subtotal = $data->subtotal_bonos;

        // Update Bonus
        if ($bonus->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Bonuses Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Bonuses Not Updated.')
            );
        }

    }else{
        // No Bonus ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Bonus ID Found')
        );
    }