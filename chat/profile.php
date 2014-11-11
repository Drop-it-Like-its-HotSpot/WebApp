<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
    //if the cookies aren't set, the user isn't logged int
    if(!isset($_COOKIE['session_id'])){
        header( 'Location: index.html' );
    }else {
        include('util/functions.php');
        $user = userProfile($_COOKIE['ID'], $_COOKIE['session_id']);
        if(!isset($user['User_id'])){
            header( 'Location: index.html' );
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
        <a href="/chat/"><img class="thelogo" src="img/logo.png"></a><br />
        <img class="titleImg" src="../img/Title.png">

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
            <form class="logoutform">
                <button class="logoutbtn" onclick='deletecookies()'>Logout</button>
            </form>
        </div>
        
        <div class="listview">
            <div class="divTopper">
                DASHBOARD
            </div>
            <div class="menubar">
                <img onclick="goHome()" class="menuIcon" src="img/home.png" alt="Home" />
                <img onclick="loadCreateChatroom()" class="menuIcon" src="img/chatplus.png" alt="Create Chat Room" />
                <img onclick="userSettings()" class="menuIcon" src="img/settings.png" alt="User Settings" />
            </div>
            <div class="content">
                <div class="chatlist">

                        <!-- data goes here-->
                
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/geo.js?id=1" type="text/javascript" charset="utf-8"></script>
    <script src="js/functions.js" type="text/javascript" charset="utf-8"></script>

    <script>
        $('a').click(function (e) {
            // custom handling here
            e.preventDefault();
        });

        $(".logoutform").submit(function(e) {
            e.preventDefault();
        });
        function deletecookies(){
            delCookie();
        }
        var latitude, longitude;
        goHome();
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
        
        function radiusScrollBar(){
            alert('changing user radius');
        }
        
        function userSettings() {
            /*
            $("#simon .1").replaceWith(function(){
                return $("<p class='3'>hello im info1</p>");
            }); 
        
            $("#button").replaceWith(function(){
                return $("<p id='button1'><a href='#'>button1</a> </p>");
            });
            */
            alert('usersettings page');
        }
        
        function goHome(){
            $.ajax({
                url: 'util/getrooms.php',
                success:function(html) {
                    $('.chatlist').html(html); // display data
                }
            });
        }
        
        function loadCreateChatroom() {
            $.ajax({
                url: 'util/createChatroom.php',
                success:function(html) {
                    $('.chatlist').html(html); // display data
                }
            });
        }
    
        function createChatroom(){
            var user = <?php echo($user['User_id']); ?>;
            var lat = <?php echo($user['Latitude']); ?>;
            var long = <?php echo($user['Longitude']); ?>;
            var title = createChat.title.value;
            var description = createChat.description.value;
            if (title == '' || description == '') {
                return;
            }
            var session_id = '<?php echo($_COOKIE['session_id']); ?>';
            $.ajax({
                url: 'http://54.172.35.180:8080/api/chatroom',
                type: "POST",
                data:{room_admin:user, latitude:lat, longitude:long, chat_title:title, chat_dscrpn:description, session_id:session_id},
                dataType: "JSON",
                success:function(html) {
                    if (html['success'] == true) {
                        goHome();
                    }else{
                        $( '.chatlist' ).append( "</br> Failed to create chatroom, try again" );
                    }
                }
            });
        }
    </script>
</body>
    
    
</html>