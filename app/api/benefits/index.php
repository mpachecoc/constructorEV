<?php

    /**
     *  Read all Benefits
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

    // Get 'read' method
    $result = $benefit->read();
    $num    = $result->rowCount();

    // Check if any Benefits
    if($num > 0){
        // Benefits array
        $benefits_arr = array();
        $benefits_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $benefits_item = array(
                'id' => $projID,
                'dias_paga_x_ano' => $socialDiasPagadosXAno,
                'dias_paga_x_apatronal' => $socialDiasPagadosXApPatronal,
                'dias_paga_x_bonos'  => $socialDiasPagadosXBonos,
                'total' => $socialTotal,
                'tot_dias_paga'  => $socialTotalDiasPagados,
                'tot_dias_habiles' => $socialTotalDiasHabiles,
                'tot_dias_paga_sin_trab' => $socialTotalDiasPagadosSinTrabajar,
                'porcentaje_carga_social'  => $socialPorcentajeDeCargaSocial
            );

            // Push to 'data'
            array_push($benefits_arr['data'], $benefits_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($benefits_arr));

    }else{
        // No Benefits
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Benefits Found')
        );
    }