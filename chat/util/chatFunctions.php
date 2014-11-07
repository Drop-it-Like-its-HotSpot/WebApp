<?php

    function getUser($ID, $session_id){
        $url = "http://54.172.35.180:8080/api/users/".$ID."/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$json_data['Latitude'].",".$json_data['Longitude']."&sensor=true";
        $json = file_get_contents($url);
        $locationData = json_decode($json, true);
        $result = $locationData['results'];
        $result = $result[6];
        $json_data['address'] = $result['formatted_address'];
        return $json_data;
    }

    function sendMessage($user, $pass){
        // The data to send to the API
        $postData = array(
            'email_id' => $user,
            'password' => $pass
        );
        
        // Setup cURL
        $ch = curl_init('54.172.35.180:8080/api/login');
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
        return $responseData;
    }

?>