<?php

    /**
     *  Update a Project Complementary (u)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectComplementary.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Complementary' object
    $project_comp = new ProjectComplementary($db);

    // Get raw json user data
    $data = json_decode(file_get_contents("php://input"));

    $project_comp->id = $data->id; // Set ID to update

    // Check if project ID exists with 'read_single' method
    $project_comp->read_single();

    if ($project_comp->exists){
        // Get rest of data to update
        $project_comp->costo_herramientas  = $data->costo_herramientas;
        $project_comp->beneficios_sociales = $data->beneficios_sociales;
        $project_comp->gastos_generales    = $data->gastos_generales;
        $project_comp->utilidad_costo_directo = $data->utilidad_costo_directo;
        $project_comp->iva = $data->iva;
        $project_comp->it  = $data->it;
        $project_comp->factor_de_paso = $data->factor_de_paso;
        $project_comp->compra_sin_factura = $data->compra_sin_factura;

        // Update Project
        if ($project_comp->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'Project Complementary Updated Successfully.')
            );
        }else{
            http_response_code(409);
            echo json_encode(
                array('message' => 'Project Complementary Not Updated.')
            );
        }

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project ID Found')
        );
    }