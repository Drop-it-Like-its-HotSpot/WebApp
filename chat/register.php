<?php
    //if the cookies are set, the user is logged in.
    if(isset($_COOKIE['ID'])){
        deletecookies();
    }else {
        include('util/functions.php');
        if(!isset($_POST['email'])){   
        }else{
            //register the user send to profile
            $login = register($_POST['email'], $_POST['password'], $_POST['displayname'], $_POST['lat'], $_POST['long']);
            if ($login){
                header( 'Location: profile.php' );
            }else{
                echo ('Whoops');
            }
        }
        
    }
?>
<html>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<link href="css/login.css" rel="stylesheet">
<div class="parent">
  <a href="/chat/"><img src="img/logo.png"></a><br />  
    <img class="titleImg" src="../img/Title.png">
    <div id="login">
      <form method="post">
        <input id="lat" name="lat" value="0" hidden/>
        <input id="long" name="long" value="0" hidden/>
        <input class="topInput" type="" name="email" placeholder="Email" value=""><br/>
        <input class="" type="" name="displayname" placeholder="Display" value=""><br/>
        <input id="password" name="password" type="password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Must have at least 6 characters' : ''); if(this.checkValidity()) form.password_two.pattern = this.value;" placeholder="Password" required></br>
        <input id="password_two" name="password_two" type="password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Please enter the same Password as above' : '');" placeholder="Verify Password" required> <br/>
        <button type="submit" onclick= >Sign In</button>
  
      </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://code.google.com/apis/gears/gears_init.js" type="text/javascript" charset="utf-8"></script>
<script src="js/geo.js?id=1" type="text/javascript" charset="utf-8"></script>
<script>
    var latitude, longitude;
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
        document.getElementById('lat').value = latitude;
        document.getElementById('long').value = longitude;
        //alert(latitude + " " + longitude);
        // proceed
    }

</script>
</body>
</html>