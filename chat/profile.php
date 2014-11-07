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

<body>
   
    <div class="header">
        <img class="titleImg" src="../img/Title.png">
        <h2>WELCOME, <?php echo strtoupper($user['DisplayName']); ?></h2>
    </div>
   
   
    <div class="container">
        <div class="profile">
            <div class="divTopper">
                PROFILE
            </div>
            <div class="content">
                <img  class="imageProfile" src="http://www.pd4pic.com/images/flat-user-theme-action-icon.png" alt="Location">
                <h3><?php echo strtoupper($user['DisplayName']); ?></h3>
                <p><?php echo $user['Email_id']; ?></p>
                <p>Location: <?php echo $user['address']; ?>
                <br/><br/>
                ID: <i><?php echo $_COOKIE['ID']; ?></i> <br />
                SESSION: <i><?php echo $_COOKIE['session_id']; ?></i>
                </p>
                <form action="logout.php">
                    <button class="logoutbtn" onclick='deletecookies()'>Logout</button>
                </form>
            </div>
        </div>
        
        <div class="listview">
            <div class="divTopper">
                CHATROOMS
            </div>
            <div class="content">
                <div class="chatlist">
                    <ul class="chatHeader">
                        Active Chats:
                        <?php
                            $nearbyChats = getJoinedChats($_COOKIE['ID'],$_COOKIE['session_id']);
                            foreach($nearbyChats as $chat){
                                if($chat['User_id'] == $_COOKIE['ID']){
                                    $chatInfo = getChatroom($chat['Room_id'], $_COOKIE['session_id']);
                                    echo('<li class="nearbyChats">');
                                    echo $chatInfo['Chat_title'];
                                    echo('</li>');
                                }
                            }
                        ?>
                    </ul>
                    <ul class="chatHeader">
                        Nearby Chats:
                        <?php
                            $nearbyChats = getNearbyChats($_COOKIE['session_id']);
                            foreach($nearbyChats as $chat){
                                echo('<li class="nearbyChats">');
                                echo $chat['Chat_title'];
                                echo('</li>');
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</body>
    
    
</html>