<?php

    /**
     *  Delete a Bonus (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialBonus.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Social Bonus' object
    $bonus = new SocialBonus($db);

    // Get Bonus ID
    $bonus->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Check if Bonus ID exists with 'read_single' method
    $bonus->read_single();

    if ($bonus->exists){
        // Delete Bonus
        if ($bonus->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Bonuses Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Bonuses Not Deleted.')
            );
        }

    }else{
        // No Bonus ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Bonus ID Found')
        );
    }