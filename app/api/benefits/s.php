<?php

    /**
     *  Read a Single Benefit (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/SocialBenefits.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Benefits' object
    $benefit = new SocialBenefits($db);

    // Get Benefit ID
    $benefit->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Benefit
    $benefit->read_single();

    if ($benefit->exists){
        // Create array
        $benefit_arr['data'] = array(
            'id' => $benefit->id,
            'dias_paga_x_ano' => $benefit->dias_paga_x_ano,
            'dias_paga_x_apatronal' => $benefit->dias_paga_x_apatronal,
            'dias_paga_x_bonos'  => $benefit->dias_paga_x_bonos,
            'total' => $benefit->total,
            'tot_dias_paga'  => $benefit->tot_dias_paga,
            'tot_dias_habiles' => $benefit->tot_dias_habiles,
            'tot_dias_paga_sin_trab' => $benefit->tot_dias_paga_sin_trab,
            'porcentaje_carga_social'  => $benefit->porcentaje_carga_social
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($benefit_arr));

    }else{
        // No Benefit ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Benefit ID Found')
        );
    }
