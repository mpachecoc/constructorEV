<?php

    /**
     *  Update a Project Cost Man Hrs. (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjCostManHrsData.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Cost Man Hrs' object
    $proj_cmh = new ProjCostManHrsData($db);

    // Get raw json project data
    $data = json_decode(file_get_contents("php://input"));

    $proj_cmh->id = $data->id; // Set ID to update

    // Check if project ID exists with 'read_single' method
    $proj_cmh->read_single();

    if ($proj_cmh->exists){
        // Get rest of data to update
        $proj_cmh->hrs_trab_x_dia  = $data->hrs_trab_x_dia;
        $proj_cmh->hrs_mes_x_persona = $data->hrs_mes_x_persona;
        $proj_cmh->hrs_trabajadas_mes = $data->hrs_trabajadas_mes;
        $proj_cmh->relac_gastos_hrs_trab = $data->relac_gastos_hrs_trab;
        $proj_cmh->comida_completa_dia = $data->comida_completa_dia;
        $proj_cmh->total_mensual  = $data->total_mensual;
        $proj_cmh->coef_ap_patronales = $data->coef_ap_patronales;
        $proj_cmh->coef_aguinaldo_liq = $data->coef_aguinaldo_liq;
        $proj_cmh->coef_desc_afp = $data->coef_desc_afp;

        // Update Project (Cost Man Hrs.)
        if ($proj_cmh->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Project (Cost Man Hrs.) Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Project (Cost Man Hrs.) Not Updated.')
            );
        }

    }else{
        // No Project ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID (Cost Man Hrs.) Found')
        );
    }