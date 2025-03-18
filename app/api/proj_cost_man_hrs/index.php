<?php

    /**
     *  Read all Projects Cost Man Hrs.
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

    // Get 'read' method
    $result = $proj_cmh->read();
    $num    = $result->rowCount();

    // Check if any projects Cost Man Hrs.
    if($num > 0){
        // Project array
        $proj_cmh_arr = array();
        $proj_cmh_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $proj_cmh_item = array(
                'id' => $projID,
                'hrs_trab_x_dia'     => $projHrsTrabajoXDia,
                'hrs_mes_x_persona'  => $projHrsMesXPersona,
                'hrs_trabajadas_mes' => $projHrsTrabajadasMes,
                'relac_gastos_hrs_trab' => $projRelacGastosHrsTrabajadas,
                'comida_completa_dia' => $projComidaCompletaDiaBs,
                'total_mensual'  => $projTotalMensual,
                'coef_ap_patronales' => $projCoeficienteAportesPatronales,
                'coef_aguinaldo_liq' => $projCoeficienteAguinaldoLiquidacion,
                'coef_desc_afp' => $projCoeficienteDescAFP
            );

            // Push to 'data'
            array_push($proj_cmh_arr['data'], $proj_cmh_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($proj_cmh_arr));

    }else{
        // No projects Cost Man Hrs.
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project (Cost Man Hrs.) Found')
        );
    }