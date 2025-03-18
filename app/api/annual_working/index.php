<?php

    /**
     *  Read all Annual Working data
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/SocialAnnualWorkingTime.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Annual Working' object
    $annual_working = new SocialAnnualWorkingTime($db);

    // Get 'read' method
    $result = $annual_working->read();
    $num    = $result->rowCount();

    // Check if any Annual Working
    if($num > 0){
        // Bonus array
        $annual_working_arr = array();
        $annual_working_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $annual_working_item = array(
                'id' => $projID,
                'year_days' => $anualYearDays,
                'inactividad' => $anualInactividad,
                'vacaciones'  => $anualVacaciones,
                'feriados' => $anualFeriados,
                'lluvias'  => $anualLluvias,
                'enfermedades' => $anualEnfermedades,
                'dias_no_trab' => $anualDiasNoTrabajados,
                'subtotal_anual_work'  => $anualSubtotal
            );

            // Push to 'data'
            array_push($annual_working_arr['data'], $annual_working_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($annual_working_arr));

    }else{
        // No Annual Working
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Annual Working data Found')
        );
    }