<?php

    /**
     *  Read all Projects
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Project.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Project' object
    $project = new Project($db);

    // Get 'read' method
    $result = $project->read();
    $num    = $result->rowCount();

    // Check if any projects
    if($num > 0){
        // Project array
        $project_arr = array();
        $project_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $project_item = array(
                'id' => $projID,
                'name' => $projName,
                'date_created' => $projDateCreated,
                'created_by'   => $projCreatedBy,
                'location' => $projLocation,
                'elaboration_date' => $projElaborationDate,
                'presup_date' => $projPresupDate,
                'end_date' => $projEndDate,
                'calendar_days' => $projCalendarDays,
                'contract_num' => $projContractNum,
                'duration' => $projDuration,
                'currency' => $projCurrency,
                'exchange_rate' => $projExchangeRate
            );

            // Push to 'data'
            array_push($project_arr['data'], $project_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($project_arr));

    }else{
        // No Users
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Projects Found')
        );
    }