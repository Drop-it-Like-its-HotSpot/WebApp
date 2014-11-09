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
<body>
   
    <div class="header">
        <a href="/chat/"><img src="img/logo.png"></a><br />
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
                $ans = inRoom($_COOKIE['ID'], $_GET['rid'], $_COOKIE['session_id']);
                if(!$ans){
                    ?><button class="joinRoom" type="submit" onclick="joinRoom()">Join Room</a><?php
                }else{
                    ?><button class="leaveRoom" type="submit" onclick="leaveRoom()">Leave Room</a><?php
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
                        <?php
                            foreach($messages as $message){
                                $user = getUserProfile($message['User_id'],$_COOKIE['session_id']);
                                if($user['User_id']== $_COOKIE['ID']){
                                    echo('<li class="chatMessageOther"><div class="messages">');
                                    echo('<i><div class="left">'.$user['DisplayName'].'</div></i>');
                                    echo('<div class="right">'.$message['Message'].'</div>');
                                    echo('</div></li>');
                                    echo('<div class="theDate">01/12/2914</div>');
                                }else{
                                    echo('<li class="chatMessageUser"><div class="messages">');
                                    echo('<i><div class="left">'.$user['DisplayName'].'</div></i>');
                                    echo('<div class="right">'.$message['Message'].'</div>');
                                    echo('</div></li>');
                                    echo('<div class="theDate">01/12/2914</div>');
                                }
                            }
                        ?>
                    </ul>
                </div>
                <?php echo ($chatInfo['Chat_Dscrpn']); ?>
            </div>
            <form class="submitmessage" id="messageform" name="messageform" method="post" onsubmit="submitChat()">
                <input class="" name="message" placeholder="Type Your Message & Press Enter" on><br/>
            </form>
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
    <script>
    $("#messageform").submit(function(e) {
        e.preventDefault();
    });
    function submitChat() {
        var message = messageform.message.value;
        if ( message == 'Type Your Message & Press Enter' ||  message == '') {
            alert('You didn\'t type a message! What are you thinking?');
            return;
        }
        alert(message);
        messageform.message.value = 'Type Your Message & Press Enter';

        
        
    }
    var $cont = $('.contentChat');
    $cont[0].scrollTop = $cont[0].scrollHeight;
    
    $('.submitmessage').keyup(function(e) {
        if (e.keyCode == 13) {
            $cont[0].scrollTop = $cont[0].scrollHeight;
        }
    })
    
    function joinRoom() {
          $.ajax({
                type: "POST", 
                url: 'util/leavejoin.php',
                data:{action:'join', room_id:'<?php echo $_GET['rid']; ?>', user_id:'<?php echo $_COOKIE['ID']; ?>', session_id:'<?php echo $_COOKIE['session_id']; ?>' },
                success:function(html) {
                    window.setTimeout(function(){location.reload()},20)
                }
    
          });
          location.reload();
     }
      function leaveRoom() {
          $.ajax({
                type: "POST", 
                url: 'util/leavejoin.php',
                data:{action:'leave', room_id:'<?php echo $_GET['rid']; ?>', user_id:'<?php echo $_COOKIE['ID']; ?>', session_id:'<?php echo $_COOKIE['session_id']; ?>' },
                success:function(html) {
                    window.location.href = "/chat/";
                }
    
          });
     }
    </script>
</body>
</html>