<?php
    //if the cookies are set, the user is logged in.
    if(isset($_COOKIE['ID'])){
        header( 'Location: profile.php' );
    }else {
        include('util/functions.php');
        include('login.html');
        
        if(isset($_POST['username']) && isset($_POST['password'])){
            $results = login($_POST['username'], $_POST['password']);
            if($results['success']){
                setmycookies($results);
                header( 'Location: profile.php' );
            }else{
                $displayerror = "Your Email and Password doesn't match our information.";
            }
        }
    }
?>
<html>
<body>
<?php
//if the login failed, display error
    if(isset($displayerror)){
        echo('<div class="parent" id="login">');
        echo($displayerror);
        echo('</div>');
    }
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

</script>
</body>
</html>