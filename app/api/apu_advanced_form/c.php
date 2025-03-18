<?php

    /**
     *  Create an APU-AdvancedForm (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ApuAdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-AdvancedForm' object
    $apu_adv_form = new ApuAdvancedForm($db);

    // Get raw json APU-AdvancedForm data
    $data = json_decode(file_get_contents("php://input"));

    $apu_adv_form->proj_id = $data->proj_id;
    $apu_adv_form->apu_id = $data->apu_id;
    $apu_adv_form->adv_form_id = $data->adv_form_id;
    $apu_adv_form->cant = $data->cant;
    $apu_adv_form->total = $data->total;
    $apu_adv_form->percent = $data->percent;


    // Create APU-AdvancedForm
    if ($apu_adv_form->create()) {
        http_response_code(201);
        echo json_encode(
            array('message' => 'Item Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Item Not Created.')
        );
    }