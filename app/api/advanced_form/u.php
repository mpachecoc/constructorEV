<?php

    /**
     *  Update an Advanced Form (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/AdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Advanced Form' object
    $adv_form = new AdvancedForm($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $adv_form->proj_id = $data->proj_id; // Set ID to update
    $adv_form->id = $data->id; // Set ID to update

    // Check if Advanced Form ID exists with 'read_single' method
    $adv_form->read_single();

    if ($adv_form->exists){
        // Get rest of data to update
        list($d,$m,$y) = sscanf($data->date_ini, "%d-%d-%d");
        $adv_form->date_ini = $y."-".$m."-".$d;
        list($d,$m,$y) = sscanf($data->date_end, "%d-%d-%d");
        $adv_form->date_end = $y."-".$m."-".$d;

        // Update Advanced Form
        if ($adv_form->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Item Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Item Not Updated.')
            );
        }

    }else{
        // No Advanced Form IDs found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }