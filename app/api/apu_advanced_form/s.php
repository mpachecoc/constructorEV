<?php

    /**
     *  Read a Single APU-AdvancedForm (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ApuAdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU-AdvancedForm' object
    $apu_adv_form = new ApuAdvancedForm($db);

    // Get APU-AdvancedForm ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & AdvancedForm id
    $exp = explode("&", $data);
    $apu_adv_form->proj_id = $exp[0];
    $apu_adv_form->apu_id  = $exp[1];
    $apu_adv_form->adv_form_id = $exp[2];

    // Get APU-AdvancedForm
    $apu_adv_form->read_single();

    if ($apu_adv_form->exists){
        // Create array
        $arr['data'] = array(
            'proj_id' => $apu_adv_form->proj_id,
            'apu_id' => $apu_adv_form->apu_id,
            'adv_form_id' => $apu_adv_form->adv_form_id,
            'cant' => $apu_adv_form->cant,
            'total' => $apu_adv_form->total,
            'percent' => $apu_adv_form->percent
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No APU-AdvancedForm found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }
