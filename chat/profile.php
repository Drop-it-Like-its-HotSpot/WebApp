<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
    include('util/functions.php');
    if(!isset($_COOKIE['ID'])){
        header("Location: index.html");
    }
    $user = userProfile($_COOKIE['ID'], $_COOKIE['session_id']);
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
                <button class="logoutbtn" onclick='userLogout()'>Logout</button>
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
        var latitude, longitude;
        goHome();
        $(document).ready(function(){
            if(getCookie('ID') == ''){
                window.location.href = "index.html";
            }
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
        
        function radiusScrollBar(){
            alert('changing user radius');
        }
        
        function userSettings() {
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
                url: 'util/createChatroom.html',
                success:function(html) {
                    $('.chatlist').html(
                        '<form method="post" id="createChat" onsubmit="return false;"> <input class="topInput" type="" name="title" placeholder="Title" value="" maxlength="30" required><br/><input class="" type="" name="description" placeholder="Short Description" value="" maxlength="30" required><br/><button type="submit" onclick="createChatroomLocal()" >Create Room</button></form>'
                    ); // display data
                }
            });
        }

        function createChatroomLocal(){
            var title = createChat.title.value;
            var description = createChat.description.value;
            if (title == '' || description == '') {
                return false;
            }
            var result = createChatroom(<?php echo($user['User_id']); ?>, <?php echo($user['Latitude']); ?>, <?php echo($user['Longitude']); ?>, title, description, '<?php echo($_COOKIE['session_id']); ?>');
            return result;
        }

    </script>
</body>
    
    
</html>