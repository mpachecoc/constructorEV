<?php

    /**
     *  Delete an APU-Supplies (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuSupply.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-Supplies' object
    $apu_supply = new ApuSupply($db);

    // Get APU-Supplies ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Supp id
    $exp = explode("&", $data);
    $apu_supply->proj_id = $exp[0];
    $apu_supply->apu_id  = $exp[1];
    $apu_supply->supp_id = $exp[2];

    // Check if IDs exists with 'read_single' method
    $apu_supply->read_single();

    if ($apu_supply->exists){
        // Delete APU-Supplies
        if ($apu_supply->delete()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Item Deleted Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Item Not Deleted.')
            );
        }

    }else{
        // No APU-Supplies ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }