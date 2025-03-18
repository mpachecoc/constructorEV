<?php

    /**
     *  Delete an Advanced Form (d)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/AdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Advanced Form' object
    $adv_form = new AdvancedForm($db);

    // Get Advanced Form ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Supp id
    $exp = explode("&", $data);
    $adv_form->proj_id = $exp[0];
    $adv_form->id = $exp[1];

    // Check if IDs exists with 'read_single' method
    $adv_form->read_single();

    if ($adv_form->exists){
        // Delete Advanced Form
        if ($adv_form->delete()) {
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
        // No Advanced Form ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }