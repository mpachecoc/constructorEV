<?php

    /**
     *  Read APUs by Group (by_group)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Apu.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU' object
    $apu = new Apu($db);

    // Get Group ID
    $apu->group_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get APUs
    $result = $apu->read_by_group();
    $num    = $result->rowCount();

    if($num > 0){
        // APU array
        $apu_arr = array();
        $apu_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $apu_item = array(
                'id' => $apuID,
                'project_id' => $projID,
                'actividad' => $apuActividad,
                'unidad' => $apuUnidad,
                'cant' => $apuCant,
                'moneda' => $apuMoneda,
                'tot_materiales' => $apuTotalMateriales,
                'tot_mano_de_obra' => $apuTotalManoDeObra,
                'tot_equipo' => $apuTotalEquipoMaquinaria,
                'tot_gastos_gral_admin' => $apuTotalGastosGeneralesAdmin,
                'tot_utilidad' => $apuTotalUtilidad,
                'tot_impuestos' => $apuTotalImpuestos,
                'tot_precio_unitario' => $apuTotalPrecioUnitario,
                'group_id' => $groupID
            );

            // Push to 'data'
            array_push($apu_arr['data'], $apu_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($apu_arr));

    }else{
        // No APUs
        http_response_code(404);
        echo json_encode(
            array('message' => 'No APUs Found')
        );
    }
