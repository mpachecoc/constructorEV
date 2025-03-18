<?php

    /**
     *  Read a Single Project Complementary (s)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectComplementary.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Complementary' object
    $project_comp = new ProjectComplementary($db);

    // Get project ID
    $project_comp->id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get project
    $project_comp->read_single();

    if ($project_comp->exists){
        // Create array
        $project_arr['data'] = array(
            'id' => $project_comp->id,
            'costo_herramientas'  => $project_comp->costo_herramientas,
            'beneficios_sociales' => $project_comp->beneficios_sociales,
            'gastos_generales'    => $project_comp->gastos_generales,
            'utilidad_costo_directo' => $project_comp->utilidad_costo_directo,
            'iva' => $project_comp->iva,
            'it'  => $project_comp->it,
            'factor_de_paso' => $project_comp->factor_de_paso,
            'compra_sin_factura' => $project_comp->compra_sin_factura
        );

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($project_arr));

    }else{
        // No User ID found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Project Complementary ID Found')
        );
    }
