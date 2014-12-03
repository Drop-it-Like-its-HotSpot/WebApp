/**
 * Created by jlkegley on 11/10/2014.
 */
//#############LOCATION HANDLING#################//
function handle_errors(error) {
    // error handling here
}
function handle_geolocation_query(position){
    var latitude1 = position.coords.latitude;
    var longitude1 = position.coords.longitude;
    latitude = latitude1;
    longitude = longitude1;
    onPositionReady(latitude1, longitude1);
}
function onPositionReady(latitude, longitude) {
    updateLocation(latitude, longitude);
}

function updateLocation(latitude, longitude) {
    var session_id = getCookie('session_id');
    $.ajax({
        type: "POST",
        url: 'http://54.172.35.180:8080/api/updatelocation',
        data:{latitude:latitude, longitude:longitude, session_id:session_id },
        dataType: "JSON",
        success:function(html) {
        }

    });
}
//##################END LOCATION HANDLING########################//
//##################HELPER METHODS, COOKIES, URL########################//
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function(m,key,value) {
            vars[key] = value;
        });
    return vars;
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
    window.location.href = "profile.html";
}

//#############################################################################################
function checkIfEmailInString(text) {
    var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
    return re.test(text);
}

//#######################USER LOGIN AND LOGOUT####################################
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
                window.location.href = "profile.html";
            }else{
                alert('Could not log you in, check your username/password');
            }
        }
    });
}

//##############################################################################


function getUserProfile(user, session_id){
    $.ajax({
        url: 'http://54.172.35.180:8080/api/users/' + session_id,
        type: "GET",
        success:function(html) {
            if(html['User_id'] == undefined){
                alert(JSON.stringify(html));
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
                        html['location'] = locationData;
                        loadUserProfile(html);
                    }
                });
            }
        }
    });
}

function loadUserProfile(userInfo){
    var display = userInfo['DisplayName'];//WHAT?
    var ID = userInfo['User_id'];
    var email = userInfo['Email_id'];
    var location = userInfo['location'];
    radius = userInfo['radius'];
    renderRadius(radius);
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
    var title = stripHTML(createChat.title.value);
    var description = stripHTML(createChat.description.value);
    //CHECKING PROPER TITLE AND DESCRIPTION INFORMATION
    if(roomC){ return false };
    var testString = title.replace(/^\s+/, '').replace(/\s+$/, '');
    if (testString === '') {
        alert("Your chatroom title should be useful! Not only spaces or blank!");
        return false;
    } else {
        // text has real content, now free of leading/trailing whitespace
    }
    if (title == '' || description == '') {
        return false;
    }
    testString = description.replace(/^\s+/, '').replace(/\s+$/, '');
    if (testString === '') {
        alert("What is your chatroom all about?");
        return false;
    } else {
        // text has real content, now free of leading/trailing whitespace
    }
    //IF ALL CHECKS OUT, SEND TO CREATE ROOM!
    var result = createChatroom(getCookie('ID'), latitude, longitude, title, description, getCookie('session_id'));
    roomC = true;
    $('.chatlist').html(
        '<div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>'
    ); // display data
    return result;
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

function stripHTML(text){
    var regex = /(<([^>]+)>)/ig;
    return text.replace(regex, "");
}


/*
 function getNearbyChats($session_id){
 $url = "http://54.172.35.180:8080/api/chatroom/".$session_id;
 $json = file_get_contents($url);
 $json_data = json_decode($json, true);
 return $json_data;
 }
 */

function loadJoinedChats(session_id){
    $.ajax({
        url: 'http://54.172.35.180:8080/api/chatroomusers/user_id/' + session_id,
        type: "GET",
        success:function(html) {

            renderJoinedOwnedChats(html);
        }
    });
}

function renderJoinedOwnedChats(nearby){
    var div = document.getElementById('chatlist');

    //WHILE LOADING JOINED ROOMS, REMOVE LOADER AND ADD ROOM
    div.innerHTML = '<span style="color: steelblue"><b>Your Rooms</b></span> | <span style="color: rgb(23,58,68)"><b>Joined Rooms</b></span>';
    div.innerHTML = div.innerHTML + '<ul class="chatHeader">Joined Chats:';

    for(var instance in nearby){
        var info = nearby[instance];
        if(info['Room_Admin'] == getCookie('ID')){
            var link = '<a href="chatroom.php?rid=' + info['chat_id'] + '"><li class="nearbyChatsOwner">';
            link = link + info['Chat_title'];
            link = link + '</li></a>';
            div.innerHTML = div.innerHTML + link;
        }else{
            var link = '<a href="chatroom.php?rid=' + info['chat_id'] + '"><li class="joinedChats">';
            link = link + info['Chat_title'];
            link = link + '</li></a>';
            div.innerHTML = div.innerHTML + link;
        }
    }
    //after loadinng Joined Room, Render Nearby Room
    loadNearbyChats(session_id);
    return;
}

function loadNearbyChats(session_id){
    $.ajax({
        url: 'http://54.172.35.180:8080/api/chatroom/' + session_id,
        type: "GET",
        success:function(html) {

            renderNearbyChats(html);
        }
    });
}

function renderNearbyChats(nearby){
    var div = document.getElementById('chatlist');
    div.innerHTML = div.innerHTML + '<ul class="chatHeader">Nearby Chats:';

    for(var instance in nearby){
        var info = nearby[instance];
        var link = '<a href="chatroom.php?rid=' + info['chat_id'] + '"><li class="nearbyChats">';
        link = link + info['Chat_title'];
        link = link + '</li></a>';
        div.innerHTML = div.innerHTML + link;

    }
    return;
}

function updateRadius(radius) {
    var session_id = getCookie('session_id');
    $.ajax({
        type: "POST",
        url: 'http://54.172.35.180:8080/api/users/put',
        data:{radius: radius, session_id:session_id },
        dataType: "JSON",
        success:function(html) {
            renderRadius(html['radius']);
        }

    });
}
