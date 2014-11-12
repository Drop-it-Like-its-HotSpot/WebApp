<?php

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

    if(isset($_POST['action'])){
        if($_POST['action'] == 'location'){
            var_dump($_POST);
            updateLocation($_POST['lat'], $_POST['long'], $_POST['session_id']);
        }
    }
    

    
?>