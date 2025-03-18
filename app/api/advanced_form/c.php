<?php

    /**
     *  Create an Advanced Form (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/AdvancedForm.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Advanced Form' object
    $adv_form = new AdvancedForm($db);

    // Get raw json Advanced Form data
    $data = json_decode(file_get_contents("php://input"));

    $adv_form->proj_id = $data->proj_id;
    $adv_form->id = $data->id;
    list($d,$m,$y) = sscanf($data->date_ini, "%d-%d-%d");
    $adv_form->date_ini = $y."-".$m."-".$d;
    list($d,$m,$y) = sscanf($data->date_end, "%d-%d-%d");
    $adv_form->date_end = $y."-".$m."-".$d;

    // Create Advanced Form
    if ($adv_form->create()) {
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