<?php
    include('chatFunctions.php');
    $users = getUsers($_GET['rid'], $_COOKIE['session_id']);
    $chatInfo = getChatroom($_GET['rid'], $_COOKIE['session_id']);
    $messages = getMessages($_GET['rid'], $_COOKIE['session_id']);
?>
<div class="messagesDiv">
<?php
ini_set('display_errors',0);
ini_set('display_startup_errors',0);
error_reporting(0);
    foreach($messages as $message){
        $user = getUserProfile($message['User_id'],$_COOKIE['session_id']);
        if($message['success'] == true) {
            if ($user['User_id'] == $_COOKIE['ID']) {
                echo('<div class="parentMessageOther"><li class="chatMessageOther"><div class="messages">');
                echo('<div class="right">' . $message['Message'] . '</div>');
                echo('</div></li>');
                echo('<i><div class="left">' . $user['DisplayName']
                    . '</div></i>');
                echo('<div class="theDate">' . fnDisplay_Timestamp(
                        $message['TimeStamp']
                    ) . '</div></div>');
            } else {
                echo('<div class="parentMessageUser"><li class="chatMessageUser"><div class="messages">');
                echo('<div class="right">' . $message['Message'] . '</div>');
                echo('</div></li>');
                echo('<i><div class="left">' . $user['DisplayName']
                    . '</div></i>');
                echo('<div class="theDate">' . fnDisplay_Timestamp(
                        $message['TimeStamp']
                    ) . '</div></div>');
            }
        }else{
            echo('YOUSUCK');
        }

    }
var_dump($user);
var_dump($message);
?>
</div>