<?php

    /**
     *  Update an APU-AdvancedForm (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuAdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-AdvancedForm' object
    $apu_adv_form = new ApuAdvancedForm($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $apu_adv_form->proj_id = $data->proj_id; // Set ID to update
    $apu_adv_form->apu_id  = $data->apu_id; // Set ID to update
    $apu_adv_form->adv_form_id = $data->adv_form_id; // Set ID to update

    // Check if APU-AdvancedForm ID exists with 'read_single' method
    $apu_adv_form->read_single();

    if ($apu_adv_form->exists){
        // Get rest of data to update
        $apu_adv_form->cant = $data->cant;
        $apu_adv_form->total = $data->total;
        $apu_adv_form->percent = $data->percent;

        // Update APU-AdvancedForm
        if ($apu_adv_form->update()) {
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
        // No APU-AdvancedForm IDs found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }