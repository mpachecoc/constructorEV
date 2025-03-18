<?php

    /**
     *  Read a Single Project Cost Man Hrs. (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ProjCostManHrsData.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Cost Man Hrs' object
    $proj_cmh = new ProjCostManHrsData($db);

    // Get project ID
    $proj_cmh->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get project
    $proj_cmh->read_single();

    if ($proj_cmh->exists){
        // Create array
        $project_arr['data'] = array(
            'id' => $proj_cmh->id,
            'hrs_trab_x_dia'  => $proj_cmh->hrs_trab_x_dia,
            'hrs_mes_x_persona' => $proj_cmh->hrs_mes_x_persona,
            'hrs_trabajadas_mes'    => $proj_cmh->hrs_trabajadas_mes,
            'relac_gastos_hrs_trab' => $proj_cmh->relac_gastos_hrs_trab,
            'comida_completa_dia' => $proj_cmh->comida_completa_dia,
            'total_mensual'  => $proj_cmh->total_mensual,
            'coef_ap_patronales' => $proj_cmh->coef_ap_patronales,
            'coef_aguinaldo_liq' => $proj_cmh->coef_aguinaldo_liq,
            'coef_desc_afp' => $proj_cmh->coef_desc_afp
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($project_arr));

    }else{
        // No Project ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID (Cost Man Hrs.) Found')
        );
    }
