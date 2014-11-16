var $cont = $('.contentChat');
$cont[0].scrollTop = $cont[0].scrollHeight;
var oldhtml;
var interval = 2000;  // 1000 = 1 second, 3000 = 3 seconds



function getChatroomInfo(room_id, session_id){


    $.ajax({
        url: 'http://54.172.35.180:8080/api/chatroom/' + room_id + '/' + session_id,
        type: "GET",
        dataType: "JSON",
        success:function(html) {
            alert(JSON.stringify(html));

            //populatePage(html);
        }
    });
}
function populatePage(html){
    userAdminID = html['Admin_id'];
}


function deleteRoom(room_id, session_id){
    if(confirm('Are you sure you want to delete this room?')){
        $.ajax({
            type: "DELETE",
            url: 'http://54.172.35.180:8080/api/chatroom/' + room_id,
            data:{session_id:session_id },
            success:function(html) {
                window.location.href = "profile.php";
            }
        });
        return true;
    }else{
        return false;
    }
}

function shouldIUpdate(html){
    if (this.oldhtml == html) {
        return 'false';
    }else{
        this.oldhtml = html;
        return 'true';
    }
}

function getNewMessages() {
    $.ajax({
        url: 'util/messages.php',
        data:{action:'leave', rid: roomID, user_id: userID, session_id: session_id },
        success:function(html) {
            status = shouldIUpdate(html);
            if (status == 'true') {
                $('.chatlist').html(html); // display data
                $cont[0].scrollTop = $cont[0].scrollHeight;
            }else{
                //no update
            }
        },
        complete: function (data) {
            // Schedule the next
            setTimeout(getNewMessages, interval);
        }
    });
}

setTimeout(getNewMessages, interval);

function submitChat() {
    var message = messageform.message.value;
    if ( message == 'Type Your Message & Press Enter' ||  message == '') {
        alert('You didn\'t type a message! What are you thinking?');
        return;
    }
    messageform.message.value = '';
    $.ajax({
        type: "POST",
        url: 'util/chatFunctions.php',
        data:{action:'sendMessage', room_id:roomID, user_id: userID, message:message, session_id:session_id },
        success:function(html) {
            getNewMessages();
        }
    });
}
function joinRoom(room) {
    $.ajax({
        type: "POST",
        url: 'http://54.172.35.180:8080/api/chatroomusers/',
        data:{room_id: roomID, user_id: userID, session_id: session_id },
        success:function(html) {
            window.setTimeout(function(){location.reload()},20)
        }
    });
    location.reload();
}


function leaveRoom(room_id, user_id, session_id) {
    $.ajax({
        crossDomain: true,
        type: "DELETE",
        url: 'http://54.172.35.180:8080/api/chatroomusers/',
        data: {room_id: room_id, user_id: user_id, session_id: session_id},
        success: function (html) {
            window.location.href = "/chat/profile.php";
        }
    });
}