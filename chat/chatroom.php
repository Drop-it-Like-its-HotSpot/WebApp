<?php
    include('util/chatFunctions.php');
    $users = getUsers($_GET['rid'], $_COOKIE['session_id']);
    $chatInfo = getChatroom($_GET['rid'], $_COOKIE['session_id']);
    $messages = getMessages($_GET['rid'], $_COOKIE['session_id']);
?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<link href="css/chats.css" rel="stylesheet">
<link rel="icon" type="image/png" href="img/logo.png">
<title>Hotspot: <?php echo ($chatInfo['Chat_title']); ?> </title>
<body>
   
    <div class="header">
        <a href="/chat/profile.php"><img class="thelogo" src="img/logo.png"></a><br />
    </div>
    

    
    <div class="container">
        <div class="profile">
            <div class="divTopper">
                Users
            </div>
            <div class="content">
                <?php
                    foreach($users as $user){
                        $userInfo = getUserProfile($user['User_id'],$_COOKIE['session_id']);
                        if($userInfo['User_id'] == $_COOKIE['ID']){
                            echo("<i><a href=\"/chat/\">".$userInfo['DisplayName']."</i></a><br />");
                        }else{
                            echo($userInfo['DisplayName']."<br />");
                        }
                    }
                ?>

            </div>
            <?php //Join and Leave functionality
                if($chatInfo['Room_Admin'] == $_COOKIE['ID']){
                    ?><button class="deleteRoom" type="submit" onclick="deleteRoom(roomID, session_id)">Delete Room</button><?php
                }
                $ans = inRoom($_COOKIE['ID'], $_GET['rid'], $_COOKIE['session_id']);
                if(!$ans){
                    ?><button class="joinRoom" type="submit" onclick="joinRoom()">Join Room</button><?php
                }else{
                    ?><button class="leaveRoom" type="submit" onclick="leaveRoomLocal(getUrlVars()['rid'], getCookie('ID'), getCookie('session_id'))">Leave Room</button><?php
                }
                
            ?>
        </div>
        
        
        
        <div class="listview">
            <div class="divTopper">
                Chat: <?php echo ($chatInfo['Chat_title']); ?>
            </div>
            <div class="contentChat">
                <div class="chatlist">
                    <ul class="chatHeader">
                        <div class="spinner">
                            <div class="double-bounce1"></div>
                            <div class="double-bounce2"></div>
                        </div>
                    </ul>
                </div>
                <?php echo ($chatInfo['Chat_Dscrpn']); ?>
            </div>
            <?php
            if($ans){?>
            <form class="submitmessage" id="messageform" name="messageform" method="post" onsubmit="submitChat()">
                <input class="" name="message" placeholder="Type Your Message & Press Enter" autocomplete="off">
            </form>
            <?php }?>
        </div>
    </div>
    
    <footer>
        <br />
        <?php $location = chatroomLocation($chatInfo['Latitude'],$chatInfo['Longitude']);
            if(isset($location[1])){
                echo($location[1]['long_name'].", ");
            }
            if(isset($location[2])){
                echo($location[2]['long_name'].", ");
            }
            if(isset($location[4])){
                echo($location[4]['long_name']);
            }
        ?>
    </footer>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/functions.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/functions.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/chatroomFunctions.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var userID = getCookie('ID');
        var roomID = getUrlVars()['rid'];
        var session_id = getCookie('session_id');
        getChatroomInfo(roomID, session_id);
        var userAdminID;
        $("#messageform").submit(function(e) {
            e.preventDefault();
        });

        function leaveRoomLocal(room_id, user_id, session_id) {
            if(!confirm('Are you sure you want to leave this room?')){
                return true;
            }else{
                leaveRoom(room_id, user_id, session_id);
            }
        }



    </script>
</body>
</html>