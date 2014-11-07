<?php
    //if the cookies are set, the user is logged in.
    if(isset($_COOKIE['ID'])){
        deletecookies();
    }else {
        include('util/functions.php');
        if(!isset($_POST['email'])){
            include('register.html');    
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
<?php
//if the login failed, display error

?>
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
        alert(latitude + " " + longitude);
        // proceed
    }

</script>
</body>
</html>