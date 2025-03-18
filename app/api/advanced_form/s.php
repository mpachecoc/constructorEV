<?php

    /**
     *  Read a Single Advanced Form (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/AdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Advanced Form' object
    $adv_form = new AdvancedForm($db);

    // Get IDs
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get APU id & Supp id
    $exp = explode("&", $data);
    $adv_form->proj_id = $exp[0];
    $adv_form->id  = $exp[1];

    // Get Advanced Form
    $adv_form->read_single();

    if ($adv_form->exists){
        // Create array
        $arr['data'] = array(
            'proj_id' => $adv_form->proj_id,
            'id' => $adv_form->id,
            'date_ini' => $adv_form->date_ini,
            'date_end' => $adv_form->date_end
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($arr));

    }else{
        // No Advanced Form found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No IDs Found')
        );
    }
