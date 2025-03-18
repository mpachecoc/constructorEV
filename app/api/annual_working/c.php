<?php

    /**
     *  Create an Annual Working (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialAnnualWorkingTime.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Annual Working' object
    $annual_working = new SocialAnnualWorkingTime($db);

    // Get raw json Annual Working data
    $data = json_decode(file_get_contents("php://input"));

    $annual_working->id = $data->id;
    $annual_working->year_days = $data->year_days;
    $annual_working->inactividad = $data->inactividad;
    $annual_working->vacaciones = $data->vacaciones;
    $annual_working->feriados = $data->feriados;
    $annual_working->lluvias = $data->lluvias;
    $annual_working->enfermedades = $data->enfermedades;
    $annual_working->dias_no_trab = $data->dias_no_trab;
    $annual_working->subtotal = $data->subtotal_anual_work;


    // Create Annual Working data
    if ($annual_working->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Annual Working data Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Annual Working data Not Created.')
        );
    }