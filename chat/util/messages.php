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
    if($messages["success"] == false && isset($messages["success"])){
        echo("No Messages");
    }else {
        foreach ($messages as $message) {
            $user = getUserProfile($message['User_id'], $_COOKIE['session_id']);
            if ($user['User_id'] != $_COOKIE['ID']) {
                echo('<div class="leftMessages">');
                echo('<div class="leftMessage">' . $message['Message']
                    . '</div>');
                echo('');
                echo('<i><div class="leftName">' . $user['DisplayName']
                    . '</div></i>');
                echo('<div class="theDate">' . fnDisplay_Timestamp(
                        $message['TimeStamp']
                    ) . '</div></div>');
            } else {
                echo('<div class="rightMessages">');
                echo('<div class="rightMessage">' . $message['Message']
                    . '</div>');
                echo('<i><div class="rightName">' . $user['DisplayName']
                    . '</div></i>');
                echo('<div class="theDate">' . fnDisplay_Timestamp(
                        $message['TimeStamp']
                    ) . '</div></div>');
            }
        }
    }
?>
</div>