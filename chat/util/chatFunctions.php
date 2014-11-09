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
   
?>