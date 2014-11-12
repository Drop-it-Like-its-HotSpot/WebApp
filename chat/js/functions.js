/**
 * Created by jlkegley on 11/10/2014.
 */

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

function userLogout(){
    delCookie();
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

//http://maps.googleapis.com/maps/api/geocode/json?latlng=".$json_data['Latitude'].",".$json_data['Longitude']."&sensor=true"
function getUserProfile(user, session_id){
    $.ajax({
        url: 'http://54.172.35.180:8080/api/users/'+ user + '/' + session_id,
        type: "GET",
        success:function(html) {
            if(html['User_id'] == undefined){
                alert('We encountered an error, please try logging in again');
                userLogout();
                window.location.href = "/chat/";
            }else {
                $.ajax({
                    url: 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + html['Latitude'] + ',' + html['Longitude'] + '&sensor=true',
                    type: "GET",
                    success:function(locationData) {
                        locationData = locationData['results'];
                        locationData = locationData[1];
                        locationData = locationData['formatted_address'];
                        html['location'] = JSON.stringify(locationData);

                        loadUserProfile(html);
                    }
                });
            }
        }
    });
}
function loadUserProfile(userInfo){
    var userProfile = document.getElementById('userDisplayName');
    var userProfile = document.getElementById('userEmail');
    var userProfile = document.getElementById('userLocation');
    var userProfile = document.getElementById('userTestingInformation');
    var display = userInfo['DisplayName'];//WHAT?
    var ID = userInfo['User_id'];
    var email = userInfo['Email_id'];
    var location = userInfo['location'];
    latitude = userInfo['Latitude'];
    longitude = userInfo['Longitude'];
    userDisplayName.innerHTML = 'WELCOME, ' + display.toUpperCase();
    userEmail.innerHTML = email;
    userLocation.innerHTML = 'Location: ' + location;
    userTestingInformation.innerHTML = 'ID: ' + ID + '<br /> Session: ' + getCookie('session_id');
    return;
}

//CREATING A CHATROOM, Calls the Ajax function to post a chatroom
function createChatroomLocal() {
    var title = createChat.title.value;
    var description = createChat.description.value;
    if (title == '' || description == '') {
        return false;
    }
    var result = createChatroom(getCookie('ID'), latitude, longitude, title, description, getCookie('session_id'));
    return result;
}