<?php

    /**
     *  Update an APU (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Apu.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'APU' object
    $apu = new Apu($db);

    // Get raw json APU data
    $data = json_decode(file_get_contents("php://input"));

    $apu->id = $data->id; // Set APU ID 
    $apu->project_id = $data->project_id; // Set Proj ID

    // Check if APU ID exists with 'read_single' method
    $apu->read_single();

    if ($apu->exists){
        // Get rest of data to update
        $apu->actividad = $data->actividad;
        $apu->unidad = $data->unidad;
        $apu->cant = $data->cant;
        $apu->moneda = $data->moneda;
        $apu->tot_materiales = $data->tot_materiales;
        $apu->tot_mano_de_obra = $data->tot_mano_de_obra;
        $apu->tot_equipo = $data->tot_equipo;
        $apu->tot_gastos_gral_admin = $data->tot_gastos_gral_admin;
        $apu->tot_utilidad = $data->tot_utilidad;
        $apu->tot_impuestos = $data->tot_impuestos;
        $apu->tot_precio_unitario = $data->tot_precio_unitario;

        // Update APU
        if ($apu->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'APU Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'APU Not Updated.')
            );
        }

    }else{
        // No APU ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No APU or Project ID Found')
        );
    }