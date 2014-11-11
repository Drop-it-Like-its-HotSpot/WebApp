<?php
    function userProfile($ID, $session_id){
        $url = "http://54.172.35.180:8080/api/users/".$ID."/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        if($json_data == NULL){
            echo('ERROR, SESSION ID EXPIRED');
            deletecookies();
        }
        //echo($json_data['Latitude'].",".$json_data['Longitude']);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$json_data['Latitude'].",".$json_data['Longitude']."&sensor=true";
        $json = file_get_contents($url);
        $locationData = json_decode($json, true);
        $result = $locationData['results'];
        $result = $result[1];
        $json_data['address'] = $result['formatted_address'];
        return $json_data;
    }
    
    function getNearbyChats($session_id){
        $url = "http://54.172.35.180:8080/api/chatroom/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        return $json_data;
    }
    
    function getJoinedChats($ID, $session_id){
        $url = "http://54.172.35.180:8080/api/chatroomusers/user_id/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        return $json_data;
    }
    
    function getChatroom($chat_id, $session_id){
        $url = "http://54.172.35.180:8080/api/chatroom/".$chat_id."/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        return $json_data;
    }

    
    function updateLocation($lat, $long, $session_id) {
        if($lat == 0 && $long == 0){
            return false;
        }
        //when ever this function is called, update the current users location
        $ch = curl_init('54.172.35.180:8080/api/updatelocation');
        $postData = array(
            'latitude' => urlencode($lat),
            'longitude' => urlencode($long),
            'session_id' => $session_id
        );
        var_dump($postData);
        // Setup cURL
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
        var_dump($response);
        $responseData = json_decode($response, TRUE);
    }
    if(isset($_POST['action'])){
        if($_POST['action'] == 'location'){
            var_dump($_POST);
            updateLocation($_POST['lat'], $_POST['long'], $_POST['session_id']);
        }
    }
    
    
    
?>