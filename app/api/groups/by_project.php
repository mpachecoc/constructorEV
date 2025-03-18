<?php

    /**
     *  Read Groups by Project (by_project)
     */

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate 'Group' object
    $group = new Group($db);

    // Get Project ID
    $group->project_id = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Get Groups
    $result = $group->read_by_project();
    $num    = $result->rowCount();

    if($num > 0){
        // Group array
        $group_arr = array();
        $group_arr['data'] = array();   // 'data' value: for pagination, version info, etc.

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $group_item = array(
                'id' => $groupID,
                'name' => $groupName,
                'project_id' => $projID
            );

            // Push to 'data'
            array_push($group_arr['data'], $group_item);
        }

        // Turn to JSON & output
        http_response_code(200);
        print_r(json_encode($group_arr));

    }else{
        // No Groups
        http_response_code(404);
        echo json_encode(
            array('message' => 'No Groups Found for this Project')
        );
    }
