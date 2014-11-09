<?php
    function joinChatroom($room_id, $user_id, $session_id){
        echo($user_id);
        // The data to send to the API
        $postData = array(
            'room_id' => $room_id,
            'user_id' => $user_id,
            'session_id' => $session_id
        );
        echo('HERE');
        var_dump($postData);
        echo('HERENOW');
        // Setup cURL
        $ch = curl_init('54.172.35.180:8080/api/chatroomusers/');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        // Send the request
        $response = curl_exec($ch);
        
        // Check for errors
        if($response === FALSE){
            die(curl_error($ch));
        }

        // Decode the response
        $responseData = json_decode($response, TRUE);
        var_dump($responseData);
    }

    function leaveChatroom($room_id, $user_id, $session_id){
        echo($user_id);
        // The data to send to the API
        $postData = array(
            'room_id' => $room_id,
            'user_id' => $user_id,
            'session_id' => $session_id
        );
        echo('HERE');
        var_dump($postData);
        echo('HERENOW');
        // Setup cURL
        $ch = curl_init('54.172.35.180:8080/api/chatroomusers/');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        // Send the request
        $response = curl_exec($ch);
        
        // Check for errors
        if($response === FALSE){
            die(curl_error($ch));
        }

        // Decode the response
        $responseData = json_decode($response, TRUE);
    }
    

    if($_POST['action'] == 'join'){
        joinChatroom($_POST['room_id'], $_POST['user_id'], $_POST['session_id']);
    }else{
        leaveChatroom($_POST['room_id'], $_POST['user_id'], $_POST['session_id']);
    }
    
    
?>