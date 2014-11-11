<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
    //grabs all of the users for a particular room
    function getUsers($room_id, $session_id){
        //54.172.35.180:8080/api/chatroomusers/room_id/7/b0e5b36e-cf3f-4bf7-adcd-659d0c3f3c10
        
        $url = "http://54.172.35.180:8080/api/chatroomusers/room_id/".$room_id."/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        return $json_data;
        
        
    }
    //grabs the chatroom from ID
    function getChatroom($room_id, $session_id){
        $url = "http://54.172.35.180:8080/api/chatroom/".$room_id."/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        return $json_data;
    }
    //get the STRING location based on GPS data
    function chatroomLocation($lat, $long) {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&sensor=true";
        $json = file_get_contents($url);
        $locationData = json_decode($json, true);
        $result = $locationData['results'];
        $result = $result[0];
        //var_dump($result);
        return $result['address_components'];
    }
    //gets messages from a particular chatroom
    function getMessages($room_id, $session_id){
        $url = "http://54.172.35.180:8080/api/messages/room_id/".$room_id."/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        return $json_data;
    }
    //gets profile information of a person
    function getUserProfile($ID, $session_id){
        $url = "http://54.172.35.180:8080/api/users/".$ID."/".$session_id;
        $json = file_get_contents($url);
        $json_data = json_decode($json, true);
        return $json_data;
    }
    //checks if a user is in a room, true or false
    function inRoom($ID, $room_id, $session_id) {
        $users = getUsers($room_id, $session_id);
        foreach ($users as $user){
            if($user['User_id'] == $ID){
                return true;
            }
        }
        return false;
    }
    //change database time to friendly time
    function fnDisplay_Timestamp($sDT){
        $date = date('m-d-Y H:i:s', strtotime($sDT));
        return $date;
    }
    //posting a message
    function postMessage($room_id, $user_id, $message, $session_id){
        $ch = curl_init('54.172.35.180:8080/api/messages/');
        // The data to send to the API
        $postData = array(
            'room_id' => $room_id,
            'user_id' => $user_id,
            'message' => $message,
            'session_id' => $session_id
        );
        echo('HERE');
        var_dump($postData);
        echo('HERENOW');
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
        $responseData = json_decode($response, TRUE);
    }
    if(isset($_POST['action'])){
        if($_POST['action'] == 'sendMessage'){
            postMessage($_POST['room_id'], $_POST['user_id'], $_POST['message'], $_POST['session_id']);
        }
    }
?>