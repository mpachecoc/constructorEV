<?php

    /**
     *  Read all Groups
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

    // Get 'read' method
    $result = $group->read();
    $num    = $result->rowCount();

    // Check if any Group
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
            array('message' => 'No Groups Found')
        );
    }