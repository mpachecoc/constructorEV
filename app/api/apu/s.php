<?php

    /**
     *  Read a Single APU (s)
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

    // Get APU ID
    $data = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Explode to get Proj id & APU id
    $exp = explode("&", $data);
    $apu->project_id = $exp[0];
    $apu->id = $exp[1];

    // Get APU
    $apu->read_single();

    if ($apu->exists){
        // Create array
        $apu_arr['data'] = array(
            'id' => $apu->id,
            'project_id' => $apu->project_id,
            'actividad' => $apu->actividad,
            'unidad' => $apu->unidad,
            'cant' => $apu->cant,
            'moneda' => $apu->moneda,
            'tot_materiales' => $apu->tot_materiales,
            'tot_mano_de_obra' => $apu->tot_mano_de_obra,
            'tot_equipo' => $apu->tot_equipo,
            'tot_gastos_gral_admin' => $apu->tot_gastos_gral_admin,
            'tot_utilidad' => $apu->tot_utilidad,
            'tot_impuestos' => $apu->tot_impuestos,
            'tot_precio_unitario' => $apu->tot_precio_unitario,
            'group_id' => $apu->group_id
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($apu_arr));

    }else{
        // No APU ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No APU o Project ID Found')
        );
    }
