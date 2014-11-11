<?php
    include('functions.php');
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
?>
<span style="color: #A87CA0"><b>Your Rooms</b></span> | <span style="color: #044F67"><b>Nearby Rooms</b></span>
<ul class="chatHeader">
    Joined Chats:
    <?php
        //TODO: Use new API call to fetch rooms I'm in. :-p 
        $joinedChats = getJoinedChats($_COOKIE['ID'],$_COOKIE['session_id']);
        var_dump($joinedChats);
        $count = 0;
        foreach($joinedChats as $chat){
            if($chat['User_id'] == $_COOKIE['ID']){
                $chatInfo = getChatroom($chat['Room_id'], $_COOKIE['session_id']);
                if($chatInfo['Room_Admin'] == $_COOKIE['ID']){    
                    echo('<a href="chatroom.php?rid='.$chatInfo['chat_id'].'&'.time().'"><li class="nearbyChatsOwner">');
                    echo $chatInfo['Chat_title'];
                    echo('</li></a>');
                    ++$count;
                }else{
                    echo('<a href="chatroom.php?rid='.$chatInfo['chat_id'].'&'.time().'"><li class="nearbyChats">');
                    echo $chatInfo['Chat_title'];
                    echo('</li></a>');
                    ++$count;
                }
            }
        }
        if($count == 0){
            echo('<br /><small>You\'re not part of any chatrooms; why don\'t you create one or join a happening room nearby!</small>');
        }
    ?>
</ul>
<ul class="chatHeader">
    Nearby Chats:
    <?php
        $nearbyChats = getNearbyChats($_COOKIE['session_id']);
        $status = false;
        $count = 0;
        foreach($nearbyChats as $chat){
            foreach($joinedChats as $joined){
                $info = getChatroom($joined['Room_id'], $_COOKIE['session_id']);
                if($joined['User_id'] == $_COOKIE['ID'] && $info['chat_id'] == $chat['chat_id']){
                    $status = true;
                }
            }
            if(!$status){
                if($chat['Room_Admin'] == $_COOKIE['ID']){    
                    echo('<a href="chatroom.php?rid='.$chat['chat_id'].'&'.time().'"><li class="nearbyChatsOwner">');
                    echo $chat['Chat_title'];
                    echo('</li></a>');
                    ++$count;
                }else{
                    echo('<a href="chatroom.php?rid='.$chat['chat_id'].'&'.time().'"><li class="nearbyChats">');
                    echo $chat['Chat_title'];
                    echo('</li></a>');
                    ++$count;
                }
            }else{
                $status = false;
            }
        }
        if($count == 0){
            echo('<br /><br /><small>Either you are in all of the nearby chatrooms or there isn\'t anything going on nearby. <br /><br />You should be a trend setter and create a room today!</small>');
        }
    ?>
</ul>