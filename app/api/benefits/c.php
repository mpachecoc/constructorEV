<?php

    /**
     *  Create a Benefit (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/SocialBenefits.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Benefits' object
    $benefit = new SocialBenefits($db);

    // Get raw json Benefit
    $data = json_decode(file_get_contents("php://input"));

    $benefit->id = $data->id;
    $benefit->dias_paga_x_ano = $data->dias_paga_x_ano;
    $benefit->dias_paga_x_apatronal = $data->dias_paga_x_apatronal;
    $benefit->dias_paga_x_bonos = $data->dias_paga_x_bonos;
    $benefit->total = $data->total;
    $benefit->tot_dias_paga = $data->tot_dias_paga;
    $benefit->tot_dias_habiles = $data->tot_dias_habiles;
    $benefit->tot_dias_paga_sin_trab = $data->tot_dias_paga_sin_trab;
    $benefit->porcentaje_carga_social = $data->porcentaje_carga_social;


    // Create Benefits
    if ($benefit->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Benefit Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Benefits Not Created.')
        );
    }