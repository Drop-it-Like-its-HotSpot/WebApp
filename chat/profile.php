<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
    //if the cookies aren't set, the user isn't logged int
    if(!isset($_COOKIE['session_id'])){
        header( 'Location: index.php' );
    }else {
        include('util/functions.php');
        $user = userProfile($_COOKIE['ID'], $_COOKIE['session_id']);
        if(!isset($user['User_id'])){
            header( 'Location: index.php' );
        }
    }
?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<link href="css/styles.css" rel="stylesheet">
<link rel="icon" type="image/png" href="img/logo.png">
<body>
   
    <div class="header">
        <a href="/chat/"><img src="img/logo.png"></a><br />
        <img class="titleImg" src="../img/Title.png">
        <div class="menubar">
            <a href="/chat/" alt="Home"><img class="menuIcon" src="img/home.png" alt="Home" /></a>
            <a href="createchat.php" alt="Create Chat Room"><img class="menuIcon" src="img/chatplus.png" alt="Create Chat Room" /></a>
            <a href="settings.php" alt="User Settings"><img class="menuIcon" src="img/settings.png" alt="User Settings" /></a>
        </div>
    </div>
   
   
    <div class="container">
        <div class="profile">
            <div class="divTopper">
                PROFILE
            </div>
            <div class="content">
                <img  class="imageProfile" src="http://www.pd4pic.com/images/flat-user-theme-action-icon.png" alt="Location">
                <h3>WELCOME, <?php echo strtoupper($user['DisplayName']); ?></h3>
                <p><?php echo $user['Email_id']; ?></p>
                <p>Location: <?php echo $user['address']; ?>
                <br/><br/>
                ID: <i><?php echo $_COOKIE['ID']; ?></i> <br />
                SESSION: <i><?php echo $_COOKIE['session_id']; ?></i>
                </p>

            </div>
            <form class="logoutForm" action="logout.php">
                <button class="logoutbtn" onclick='deletecookies()'>Logout</button>
            </form>
        </div>
        
        <div class="listview">
            <div class="divTopper">
                CHATROOMS
            </div>
            <div class="content">
                <div class="chatlist">
                    <span style="color: #A87CA0"><b>Your Rooms</b></span> | <span style="color: #044F67"><b>Nearby Rooms</b></span>
                    <ul class="chatHeader">
                        Joined Chats:
                        <?php
                            //TODO: Use new API call to fetch rooms I'm in. :-p 
                            $joinedChats = getJoinedChats($_COOKIE['ID'],$_COOKIE['session_id']);
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
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://code.google.com/apis/gears/gears_init.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/geo.js?id=1" type="text/javascript" charset="utf-8"></script>
    <script>
        var latitude, longitude;
        $(document).ready(function(){
            
            if(geo_position_js.init()){
                    geo_position_js.getCurrentPosition(handle_geolocation_query,handle_errors,{enableHighAccuracy:true});
            }
            else{
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(handle_geolocation_query,handle_errors);
                } else {
                    alert('Device probably not ready.');
                }
            }
    
    
        });
        function handle_errors(error) {  
            // error handling here
        }
        function handle_geolocation_query(position){  
            latitude = (position.coords.latitude);
            longitude = (position.coords.longitude); 
            onPositionReady();
        }
        function onPositionReady() {
            updateLocation(latitude, longitude);
        }
        
        function updateLocation(latitude, longitude) {
          $.ajax({
                type: "POST", 
                url: 'util/functions.php',
                data:{action:'location', lat:latitude, long:longitude, session_id:'<?php echo $_COOKIE['session_id']; ?>' },
                success:function(html) {
                    //do something to update
                }
    
          });
     }
    </script>
</body>
    
    
</html>