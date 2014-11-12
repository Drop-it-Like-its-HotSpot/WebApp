/**
 * Created by jlkegley on 11/10/2014.
 */

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*60*60*1000));//add * 24 for one day
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function delCookie() {
    var d = new Date();
    d.setTime(d.getTime() + (1));
    var expires = "expires="+d.toUTCString();
    document.cookie = 'ID' + "=" + "NULL" + "; " + expires;
    document.cookie = 'session_id' + "=" + "NULL" + "; " + expires;
    window.location.href = "profile.php";
}


function userLogin(uemail, upassword){
    var email_id = uemail;
    var password = upassword;
    $.ajax({
        url: 'http://54.172.35.180:8080/api/login',
        type: "POST",
        data:{email_id:email_id, password:password},
        dataType: "JSON",
        success:function(html) {
            if (html['success'] == true) {

                setCookie('ID', html['user_id'], 1);
                setCookie('session_id', html['session_id'], 1);
                window.location.href = "profile.php";
            }else{
                alert('Could not log you in, check your username/password');
            }
        }
    });
}


function updateLocation(latitude, longitude) {
    $.ajax({
        type: "POST",
        url: 'http://54.172.35.180:8080/api/updatelocation',
        data:{lat:latitude, long:longitude, session_id:getCookie('session_id') },
        dataType: "JSON",
        success:function(html) {
            return html;
        }

    });
}

function leaveRoom(room_id, user_id, session_id) {
    $.ajax({
        crossDomain: true,
        type: "DELETE",
        url: 'http://54.172.35.180:8080/api/chatroomusers/',
        data: {room_id: room_id, user_id: user_id, session_id: session_id},
        success: function (html) {
            window.location.href = "/chat/";
        }
    });
}

function createChatroom(user, lat, long, title, description, session_id){
    $.ajax({
        url: 'http://54.172.35.180:8080/api/chatroom',
        type: "POST",
        data:{room_admin:user, latitude:lat, longitude:long, chat_title:title, chat_dscrpn:description, session_id:session_id},
        dataType: "JSON",
        success:function(html) {
            if (html['success'] == true) {
                goHome();
                return true;
            }else{
                return false;
            }
        }
    });
}