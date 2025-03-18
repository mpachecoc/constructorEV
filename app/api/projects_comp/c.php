<?php

    /**
     *  Create a Project Complementary (c)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/ProjectComplementary.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project Complementary' object
    $project_comp = new ProjectComplementary($db);

    // Get raw json project complementary data
    $data = json_decode(file_get_contents("php://input"));

    $project_comp->id = $data->id;
    $project_comp->costo_herramientas  = $data->costo_herramientas;
    $project_comp->beneficios_sociales = $data->beneficios_sociales;
    $project_comp->gastos_generales    = $data->gastos_generales;
    $project_comp->utilidad_costo_directo = $data->utilidad_costo_directo;
    $project_comp->iva = $data->iva;
    $project_comp->it  = $data->it;
    $project_comp->factor_de_paso = $data->factor_de_paso;
    $project_comp->compra_sin_factura = $data->compra_sin_factura;


    // Create Project
    if ($project_comp->create()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Project Complementary Created Successfully.')
        );
    }else{
        http_response_code(409);
        echo json_encode(
            array('message' => 'Project Complementary Not Created.')
        );
    }